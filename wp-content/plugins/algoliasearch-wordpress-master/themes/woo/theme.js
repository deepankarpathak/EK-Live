jQuery(document).ready(function ($) {

    var autocomplete = true;
    var instant = true;

    if (algoliaSettings.type_of_search.indexOf("autocomplete") !== -1)
    {
        var $autocompleteTemplate = Hogan.compile($('#autocomplete-template').text());

        var hogan_objs = [];

        algoliaSettings.indices.sort(indicesCompare);

        var indices = [];
        for (var i = 0; i < algoliaSettings.indices.length; i++)
            indices.push(algolia_client.initIndex(algoliaSettings.indices[i].index_name));

        for (var i = 0; i < algoliaSettings.indices.length; i++) {
            hogan_objs.push({
                source: indices[i].ttAdapter({hitsPerPage: algoliaSettings.number_by_type}),
                displayKey: 'title',
                templates: {
                    header: '<div class="category">' + algoliaSettings.indices[i].name + '</div>',
                    suggestion: function (hit) {
                        return $autocompleteTemplate.render(hit);
                    }
                }
            });

        }

        hogan_objs.push({
            source: getBrandingHits(),
            displayKey: 'title',
            templates: {
                suggestion: function (hit) {
                    return '<div class="footer">powered by <img width="45" src="' + algoliaSettings.plugin_url + '/front/algolia-logo.png"></div>';
                }
            }
        });

        function activateAutocomplete()
        {
            $(algoliaSettings.search_input_selector).each(function (i) {
                $(this).typeahead({hint: false}, hogan_objs);

                $(this).on('typeahead:selected', function (e, item) {
                    autocomplete =true;
                    instant = true;
                    window.location.href = item.permalink;
                });
            });
        }

        activateAutocomplete();

        function desactivateAutocomplete()
        {
            $(algoliaSettings.search_input_selector).each(function (i) {
                $(this).typeahead('destroy')
            });
        }
    }

    if (algoliaSettings.type_of_search.indexOf("instant") !== -1)
    {
        window.facetsLabels = {
            'post': 'Article',
            'page': 'Page'
        };

        /**
         * Variables Initialization
         */

        var old_content         = $(algoliaSettings.instant_jquery_selector).html();

        var resultsTemplate     = Hogan.compile($('#instant-content-template').text());
        var facetsTemplate      = Hogan.compile($('#instant-facets-template').text());
        var paginationTemplate  = Hogan.compile($('#instant-pagination-template').text());

        var conjunctive_facets  = [];
        var disjunctive_facets  = [];

        for (var i = 0; i < algoliaSettings.facets.length; i++)
        {
            if (algoliaSettings.facets[i].type == "conjunctive")
                conjunctive_facets.push(algoliaSettings.facets[i].tax);

            if (algoliaSettings.facets[i].type == "disjunctive")
                disjunctive_facets.push(algoliaSettings.facets[i].tax);

            if (algoliaSettings.facets[i].type == "slider")
                disjunctive_facets.push(algoliaSettings.facets[i].tax);

            if (algoliaSettings.facets[i].type == "menu")
                disjunctive_facets.push(algoliaSettings.facets[i].tax);

            if (algoliaSettings.facets[i].type == "select")
                disjunctive_facets.push(algoliaSettings.facets[i].tax);
        }

        algoliaSettings.facets = algoliaSettings.facets.sort(facetsCompare);

        var helper = algoliasearchHelper(algolia_client, algoliaSettings.index_name + 'all', {
            facets: conjunctive_facets,
            disjunctiveFacets: disjunctive_facets,
            hitsPerPage: algoliaSettings.number_by_page
        });

        /**
         * Functions
         */

        function performQueries(push_state)
        {
            engine.helper.search(engine.helper.state.query, searchCallback);

            engine.updateUrl(push_state);
        }

        $(function()
        {
            $('.scroll-pane')
            .jScrollPane()
            .bind(
                'mousewheel',
                function(e)
                {
                    e.preventDefault();
                }
            );
        });

        function searchCallback(content)
        {
            var html_content = "";

            html_content += "<div id='algolia_instant_selector'><div class='univ_logo_outer  clearfix'><div class='university_logo_desc row'></div></div><div class='row'><div class='banner_img_container custom-hide-small' style='text-align:center;'></div>";

            var facets = [];
            var pages = [];

            if (content.hits.length > 0)
            {
                facets = engine.getFacets(content);
                pages = engine.getPages(content);

                html_content += engine.getHtmlForFacets(facetsTemplate, facets,content);
            }

            html_content += engine.getHtmlForResults(resultsTemplate, content, facets);
            
            list=0
            if($('#view li')[0]!==undefined)
            {
                if($($('#view li')[0]).hasClass("list"))
                    list=1;
            }            
            if (content.hits.length > 0)
                html_content += engine.getHtmlForPagination(paginationTemplate, content, pages, facets);

            html_content += "</div></div>";
            $(algoliaSettings.instant_jquery_selector).html(html_content);
            
            // Create labels on algolia search page:Gambheer
            // Get selected filters
            $(".sub_facet").find("input[type='checkbox']").each(function (i) {
               if($(this).is(':checked') == true){
                var data_name = $(this).attr("data-name");
                var data_tax = $(this).attr("data-tax");
                var raw_label_html = $(".raw_labels").html();
                if($(".raw_labels").html().indexOf(data_name)<=0){
                    $(".raw_labels").html(raw_label_html+"<div class='label' data-tax='"+data_tax+"' data-name='"+data_name+"'>"+data_name+"<span class='close_label'>x</span></div>");
                    }
               }
            });

            $(".sub_facet_mobile").find("input[type='checkbox']").each(function (i) {
               if($(this).is(':checked') == true){
                    $(this).parent().addClass('change_color');
                }
            });       


            // Get Labels from footer on load of algolia search filter
             if($(".raw_labels").html() != ""){
               $(".labels").css("margin-bottom", "15px");
             }
             else{
               $(".labels").css("margin-bottom", "0px");   
             }
            $(".labels").html($(".raw_labels").html());
            // Get Banner from footer on load of algolia search filter
            $(".banner_img_container").html($(".raw_banner_image").html());
            /*setTimeout(
                function(){
                $(".banner_img_container").html($(".raw_banner_image").html());
            }, 2000);*/
                        
            //Get university institute logo and description
           
            if($(".raw_university_logo_desc .univ_logo img").attr("src") != undefined){
                $(".univ_logo_outer").show();
                $(".university_logo_desc").html($(".raw_university_logo_desc").html());
                if($(".raw_banner_image img").attr("src") == ""){
                    $(".banner_img_container").hide();
                }
            }
            if($(".raw_university_logo_desc .univ_logo img").attr("src") == "")
                $(".univ_logo_outer").hide();


            updateSliderValues();
            $(".algolia-slider").parent().prev().css("display","none");

            if(list)
            {
               $("button.list").trigger("click");
            }
        }

        function activateInstant()
        {
            helper.on('result', searchCallback);
        }

        activateInstant();

        function desactivateInstant()
        {
            helper.removeAllListeners();

            location.replace('#');

            $(algoliaSettings.instant_jquery_selector).html(old_content);
            
        }

        engine.setHelper(helper);


        /**
         * Custom Facets Types
         */

        custom_facets_types["slider"] = function (engine, content, facet) {
            if (content.getFacetByName(facet.tax) != undefined)
            {
                var min = content.getFacetByName(facet.tax).stats.min;
                var max = content.getFacetByName(facet.tax).stats.max;

                var current_min = engine.helper.state.getNumericRefinement(facet.tax, ">=");
                var current_max = engine.helper.state.getNumericRefinement(facet.tax, "<=");

                if (current_min == undefined)
                    current_min = min;

                if (current_max == undefined)
                    current_max = max;

                var params = {
                    type: {},
                    current_min: Math.floor(current_min),
                    current_max: Math.ceil(current_max),
                    count: min == max ? 0 : 1,
                    min: Math.floor(min),
                    max: Math.ceil(max)
                };

                params.type[facet.type] = true;

                return [params];
            }

            return [];
        };

         custom_facets_types["select"] = function (engine, content, facet) {

            var data = [];

            var all_count = 0;
            var all_unchecked = true;

            var content_facet = content.getFacetByName(facet.tax);

            if (content_facet == undefined)
                return data;

            for (var key in content_facet.data)
            {
                var checked = engine.helper.isRefined(facet.tax, key);

                all_unchecked = all_unchecked && !checked;

                var name = window.facetsLabels && window.facetsLabels[key] != undefined ? window.facetsLabels[key] : key;
                var nameattr = key;

                var params = {
                    type: {},
                    checked: checked,
                    nameattr: nameattr,
                    name: name,
                    print_count: true,
                    count: content_facet.data[key]
                };

                all_count += content_facet.data[key];

                params.type[facet.type] = true;

                data.push(params);
            }

            var params = {
                type: {},
                checked: all_unchecked,
                nameattr: 'all',
                name: 'All',
                print_count: false,
                count: all_count
            };

            params.type[facet.type] = true;

            data.unshift(params);

            return data;
        };

        custom_facets_types["menu"] = function (engine, content, facet) {

            var data = [];

            var all_count = 0;
            var all_unchecked = true;

            var content_facet = content.getFacetByName(facet.tax);

            if (content_facet == undefined)
                return data;

            for (var key in content_facet.data)
            {
                var checked = engine.helper.isRefined(facet.tax, key);

                all_unchecked = all_unchecked && !checked;

                var name = window.facetsLabels && window.facetsLabels[key] != undefined ? window.facetsLabels[key] : key;
                var nameattr = key;

                var params = {
                    type: {},
                    checked: checked,
                    nameattr: nameattr,
                    name: name,
                    print_count: true,
                    count: content_facet.data[key]
                };

                all_count += content_facet.data[key];

                params.type[facet.type] = true;

                data.push(params);
            }

            var params = {
                type: {},
                checked: all_unchecked,
                nameattr: 'all',
                name: 'All',
                print_count: false,
                count: all_count
            };

            params.type[facet.type] = true;

            data.unshift(params);

            return data;
        };

        /**
         * Bindings
         */

          $("body").on("click", ".sub_facet.select", function (e) {

            e.stopImmediatePropagation();

            if ($(this).attr("data-name") == "all")
                engine.helper.state.clearRefinements($(this).attr("data-tax"));

            $(this).find("input[type='checkbox']").each(function (i) {
                $(this).prop("checked", !$(this).prop("checked"));

                if (false == engine.helper.isRefined($(this).attr("data-tax"), $(this).attr("data-name")))
                    engine.helper.state.clearRefinements($(this).attr("data-tax"));

                if ($(this).attr("data-name") != "all")
                    engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
            });

            performQueries(true);
        });

        $("body").on("click", ".sub_facet.menu", function (e) {

            e.stopImmediatePropagation();

            if ($(this).attr("data-name") == "all")
                engine.helper.state.clearRefinements($(this).attr("data-tax"));

            $(this).find("input[type='checkbox']").each(function (i) {
                $(this).prop("checked", !$(this).prop("checked"));

                if (false == engine.helper.isRefined($(this).attr("data-tax"), $(this).attr("data-name")))
                    engine.helper.state.clearRefinements($(this).attr("data-tax"));

                if ($(this).attr("data-name") != "all")
                    engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
            });

            performQueries(true);
        });

        $("body").on("click", ".sub_facet", function () {

            $(this).find("input[type='checkbox']").each(function (i) {
                $(this).prop("checked", !$(this).prop("checked"));

                engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
            });

            performQueries(true);
        });

        // Mobile filters
        $("body").on("click", ".sub_facet_mobile", function () {
                $(this).toggleClass('change_color');
                $(this).find("input[type='checkbox']").each(function (i) {
                $(this).prop("checked", !$(this).prop("checked"));

                engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
            });

            $("body").on("click", ".apply", function () {
                engine.helper.search(engine.helper.state.query);
                engine.updateUrl(true);
            });
        });
        
        $("body").on("click", ".reset", function () {
            $(".sub_facet_mobile").find("input[type='checkbox']").each(function (i) {
               if($(this).is(':checked') == true){
                    $(this).toggleClass('change_color');
                    $(this).prop("checked", !$(this).prop("checked"));
                    engine.helper.toggleRefine($(this).attr("data-tax"), $(this).attr("data-name"));
                }
            });
            var slide_dom = $(".algolia-slider");
            engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), ">=");
            engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), "<=");
            engine.helper.search(engine.helper.state.query);
            engine.updateUrl(true);
        });

        $("body").on("click", ".mobile-filter .facet", function () {
            $(".mobile-filter .facet").removeClass("active-tab");
            $(this).addClass("active-tab");
            $(".mobile-filter .facet .name").css({"color":"#2c3e50","font-family":"robotoregular"});
            $(this).find(".name").css({"color":"#169f84","font-family":"robotobold"});
            if($(this).attr("id").indexOf(" ") > 0)
              var cls = $(this).attr("id").substr(0, $(this).attr("id").indexOf(" "));
            else
              var cls = $(this).attr("id");  
            
            $(".all_results").fadeOut();
            $(".result_"+cls).fadeIn();
        });
        // Mobile filters end

        $("body").on("slide", "", function (event, ui) {
            updateSlideInfos(ui);
        });

        $("body").on("change", "#index_to_use", function () {
            engine.helper.setIndex($(this).val());

            engine.helper.setCurrentPage(0);

            performQueries(true);
        });

        $("body").on("slidechange", ".algolia-slider-true", function (event, ui) {

            var slide_dom = $(ui.handle).closest(".algolia-slider");
            var min = slide_dom.slider("values")[0];
            var max = slide_dom.slider("values")[1];

            if (parseInt(slide_dom.slider("values")[0]) >= parseInt(slide_dom.attr("data-min")))
                engine.helper.addNumericRefinement(slide_dom.attr("data-tax"), ">=", min);
            if (parseInt(slide_dom.slider("values")[1]) <= parseInt(slide_dom.attr("data-max")))
                engine.helper.addNumericRefinement(slide_dom.attr("data-tax"), "<=", max);

            if (parseInt(min) == parseInt(slide_dom.attr("data-min")))
                engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), ">=");

            if (parseInt(max) == parseInt(slide_dom.attr("data-max")))
                engine.helper.removeNumericRefinement(slide_dom.attr("data-tax"), "<=");

            updateSlideInfos(ui);
            performQueries(true);
        });

        $("body").on("click", ".algolia-pagination a", function (e) {
            e.preventDefault();

            engine.gotoPage($(this).attr("data-page"));
            performQueries(true);

            $("body").scrollTop(0);

            return false;
        });

  /*      $('button').on('click',function(e) {
    if ($(this).hasClass('grid')) {
        alert("GRID");
        $('#view ul').removeClass('list').addClass('grid');
    }
    else if($(this).hasClass('list')) {
        alert("LIST");
        $('#view ul').removeClass('grid').addClass('list');
    }
    else
    {
        alert("Here!");
    }
});*/
        
        $(algoliaSettings.search_input_selector).keyup(function (e) {
            e.preventDefault();

            if (instant === false)
                return;

            var $this = $(this);

            engine.helper.setQuery($(this).val());

            $(algoliaSettings.search_input_selector).each(function (i) {
                if ($(this)[0] != $this[0])
                    $(this).val(engine.helper.state.query);
            });

            if ($(this).val().length == 0) {

                clearTimeout(history_timeout);

                location.replace('#');

                $(algoliaSettings.instant_jquery_selector).html(old_content);

                return;
            }

            /* Uncomment to clear refinements on keyup */

            //engine.helper.clearRefinements();


            performQueries(false);

            return false;
        });

        function updateSliderValues()
        {
            $(".algolia-slider-true").each(function (i) {
                var min = $(this).attr("data-min");
                var max = $(this).attr("data-max");

                var new_min = engine.helper.state.getNumericRefinement($(this).attr("data-tax"), ">=");
                var new_max = engine.helper.state.getNumericRefinement($(this).attr("data-tax"), "<=");

                if (new_min != undefined)
                    min = new_min;

                if (new_max != undefined)
                    max = new_max;

                $(this).slider({
                    min: parseInt($(this).attr("data-min")),
                    max: parseInt($(this).attr("data-max")),
                    range: true,
                    values: [min, max]
                });
            });
        }

        function updateSlideInfos(ui)
        {
            var infos = $(ui.handle).closest(".algolia-slider").nextAll(".algolia-slider-info");

            infos.find(".min").html(ui.values[0]);
            infos.find(".max").html(ui.values[1]);
        }

        /**
         * Initialization
         */

        $(algoliaSettings.search_input_selector).attr('autocomplete', 'off').attr('autocorrect', 'off').attr('spellcheck', 'false').attr('autocapitalize', 'off');

        engine.getRefinementsFromUrl();

        window.addEventListener("popstate", function(e) {
            engine.getRefinementsFromUrl();
        });
    }

    if (algoliaSettings.type_of_search.indexOf("autocomplete") !== -1 && algoliaSettings.type_of_search.indexOf("instant") !== -1)
    {
        if (location.hash.length <= 1)
        {
            desactivateInstant();
            instant = false;
        }
        else
        {
            autocomplete = false;
            desactivateAutocomplete();
            $(algoliaSettings.search_input_selector+':first').focus();
        }
    }

    // Hide mega menu if we directly open any product page
    if($(".search_home").val() != ""){
        $("#mega-menu").hide();  
    }    
 
    // Quick View in algolia search page

    $("body").on("click", ".quick_view_link", function (e) {
       /* add loader  */
       $(this).after('<div class="loading dark" style="background:green; color:red;"><i></i><i></i><i></i><i></i></div>');
       $(this).parent().css("display","block");
       var product_id = $(this).attr('data-prod');
       var data = { action: 'jck_quickview', product: product_id};
        $.post(ajaxURL.ajaxurl, data, function(response) {
            $(".quick_view_overlay_display_data").fadeIn("slow");
            $(".quick_view_overlay_display_data .edu_quickview").remove();
            $(".data_quick_view").html($(".data_quick_view").html()+response);
            $(".loading, .dark").remove();
            $(".quick_view_overlay").removeAttr("style");
        });
        
        $("body").on("click", ".close_quick_view", function (e) {
            $(".quick_view_overlay_display_data").fadeOut("slow");
        });
    });

});