<script type="text/template" id="autocomplete-template">
    <div class="result">
        <div class="title">
            {{#featureImage}}
            <div class="thumb">
                <img style="width: 30px" src="{{{ featureImage.sizes.thumbnail.file }}}" />
            </div>
            {{/featureImage}}
            <div class="info{{^featureImage}}-without-thumb{{/featureImage}}">
            {{{ _highlightResult.title.value }}}
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</script>

<script type="text/template" id="instant-content-template">

    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 min-pad hits{{#facets_count}} with_facets{{/facets_count}}">
        <span class="filter-icon sprite"></span>
        <div style="clear: both;"></div>
        <div class="labels custom-hide-small"></div>

        <ul id="view" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
            {{#hits}}
            
            <li class="col-lg-4 col-md-4 col-sm-6 col-xs-12 <?php if ( wp_is_mobile() ){echo 'list';}else{echo 'grid';}?>">
                    <div class="result">
                        <div class="result-content clearfix">          
                            <div class="<?php if ( wp_is_mobile() ){echo 'result-sub-content-list';}else{echo 'result-sub-content-grid';}?> clearfix">
                                <div class="result-thumbnail">
                                    <div class="<?php if ( wp_is_mobile() ){echo 'list-images';}else{echo 'grid-images';}?>">
                                        {{#featureImage}}
                                            <img src="{{{ featureImage.file }}}" />

                                        {{/featureImage}}
                                    </div>
                                    {{^featureImage}}
                                    <div style="height: 50px;"></div>
                                {{/featureImage}}
                                </div>
                                <div class="result-excerpt clearfix">
                                    <div class="title-institute-wrapper">
                                    <h4 class="result-title">
                                        {{{ _highlightResult.title.value }}} - {{pa_specialization}}
                                    </h4>
                                    <div class="institute">{{university}}</div>
                                    </div>
                                    <div class="mode-duration-wrapper">
                                    <div class="mode">{{pa_study-content}}</div>
                                    <div class="duration"><img class="calander-img" alt="calander-img" src="<?php echo get_site_url().'/images/product-detail-calander.png'; ?>"{{pa_duration}}</div>
                                    <div class="price-coupan-wrapper">
                                    {{#pa_referral-cashback.length}}
                                    <div class="referral custom-hide-small"><span class="cashback">Cashback</span> Rs. {{pa_referral-cashback}}</div>
                                    {{/pa_referral-cashback.length}}
                                    <div class="<?php if ( wp_is_mobile() ){echo 'list-grid';}else{echo 'price-grid';}?>"> Rs. {{_price}}</div>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="quick_view_overlay">
                                    <div class="btn-wrapper"> 
                                    <?php if ( !wp_is_mobile() ){ ?>                       
                                        <div class="compare_link link-btn" data-prod={{objectID}}>Compare</div>
                                        <div class="quick_view_link link-btn" data-prod={{objectID}}>Quick View</div>
                                    <?php }?>
                                        <div class="learn_more_link link-btn"><a href="{{permalink}}" target="_blank">Learn More</a>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>               
                </a>
            </li>
            
            {{/hits}}
        </ul>

        
        <div style="clear: both;"></div>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 min-pad right-pad-none custom-hide-small">
        <div class="button-123 hidden-xs">
         {{#hits.length}}
           {{#sorting_indices.length}}
            <div class="default_sorting">
                <select id="index_to_use">
                    <option {{#sortSelected}}{{relevance_index_name}}{{/sortSelected}} value="{{relevance_index_name}}">Sort Price</option>
                    {{#sorting_indices}}
                    <option {{#sortSelected}}{{index_name}}{{/sortSelected}} value="{{index_name}}">{{label}}</option>
                    {{/sorting_indices}}
                </select>
            </div>
            {{/sorting_indices.length}}
            {{/hits.length}}
            <button class="list changelook" onclick="$('#view li').removeClass('grid').addClass('list'); $('#view .grid-images').removeClass('grid-images').addClass('list-images'); $('#view .result-sub-content-grid').removeClass('result-sub-content-grid').addClass('result-sub-content-list'); $('#view .price-grid').removeClass('price-grid').addClass('price-list');"><img src="<?php echo get_site_url(); ?>/images/list.png"/></button>
            <button class="grid changelook" onclick="$('#view li').removeClass('list').addClass('grid'); $('#view .list-images').removeClass('list-images').addClass('grid-images'); $('#view .result-sub-content-list').removeClass('result-sub-content-list').addClass('result-sub-content-grid'); $('#view .price-list').removeClass('price-list').addClass('price-grid');" ><img src="<?php echo get_site_url(); ?>/images/grid.png"/></button>
        </div>
        <?php echo do_shortcode('[block id="advertisement"]'); ?>
    </div>

{{^hits.length}}
        <div class="row">
            <div class="col-lg-12 col-md-12 ">Sorry, You’re looking for <strong>{{query}}</strong> which isn’t here. However, we have wide range of courses which will help you enhance your skills.<br/><br/>For Certificates, <a href="http://edukart.com/edukart/#q=certificate&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22
">Click here</a>  |   For Entrance Coaching, <a href="http://edukart.com/edukart/#q=entrance&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here.</a>  |  For School Education, <a href="http://edukart.com/edukart/#q=class&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a>   |   For Degree Programs, <a href="http://edukart.com/edukart/#q=degree&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a>  |   For Diplomas, <a href="http://edukart.com/edukart/#q=diploma&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a><br/>
            </div>
        </div>
        {{/hits.length}}
</script>

<script type="text/template" id="instant-facets-template">
{{#hits.length}}
        <div class="infos">
            <div id="result_for">
            <?php 
                if(!empty($_SESSION['alogolia_notfound'])):
                    echo $_SESSION['alogolia_notfound']." ({{nbHits}} courses)";
                    unset($_SESSION['alogolia_notfound']);
                else:
            ?>           
            Result{{^nbHits_one}}s{{/nbHits_one}} for <span>{{query}}</span> ({{nbHits}} courses)
            <?php endif; ?>
            </div>
            
            
        </div>
        
        {{/hits.length}}
<?php if(!wp_is_mobile()) { ?>

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 left-pad-none min-pad desk-filter-wrapper facets{{#count}} with_facets{{/count}}">
    <div class="clear_all_div">
       <button class="clear_all" onclick="clear_all()"><span class="refresh sprite"></span><span class="clear-btn">Clear All</span></button>
    </div> 
    
    {{#facets}}
    {{#count}}

    <div class="facet">
        <div class="name" onclick="dock_undock(this)">
            {{ facet_categorie_name }}
            
            <button class="dock_undock"></button>     
        </div>
        <div class="dock_this">
          <span id="clear" class="clear_filter" onclick="clear_filter(this)">CLEAR</span>
          <div class="course-search">
            <input type="text" id="filter_filter" class="filter_filter" placeholder="Type {{ facet_categorie_name }}" onkeyup="filter(this)" />    
          </div>         
          <div class = "scroll-pane" >
                {{#sub_facets}}

                {{#type.menu}}
                <div data-tax="{{tax}}" data-name="{{nameattr}}" data-type="menu" class="{{#checked}}checked {{/checked}}sub_facet menu">
                    <input style="display: none;" data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                    {{name}} {{#print_count}}({{count}}){{/print_count}}
                </div>
                {{/type.menu}}

                {{#type.conjunctive}}
                <div data-name="{{tax}}" data-type="conjunctive" class="{{#checked}}checked {{/checked}}sub_facet conjunctive">
                    <input style="display: none;" data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                    {{name}} ({{count}})
                </div>
                {{/type.conjunctive}}
                

                {{#type.slider}}
                <div class="algolia-slider algolia-slider-true" data-tax="{{tax}}" data-min="{{min}}" data-max="{{max}}" id="term"></div>
                <div class="algolia-slider-info">
                    <div class="min" style="float: left;">{{current_min}}</div>
                    <div class="max" style="float: right;">{{current_max}}</div>
                    <div style="clear: both"></div>
                </div>
                {{/type.slider}}

                <div class="options">
                {{#type.disjunctive}}
                <div data-name="{{tax}}" data-type="disjunctive" class="{{#checked}}checked {{/checked}}sub_facet disjunctive">
                    <input data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                    {{name}} ({{count}})
                </div>
                {{/type.disjunctive}}
                </div>
                

            {{/sub_facets}}
        </div>
    </div>
</div>
    {{/count}}
    {{/facets}}
</div>
<div class="quick_view_overlay_display_data">
    <div class="data_quick_view">
        <div class="close_quick_view">X</div>        
    </div>
</div>
<?php } else{ ?>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 facets{{#count}} with_facets{{/count}} mobile-filter">
    <div class="filter-head clearfix">
        <div class="pull-left filters-titl"><span class="sprite close_filter"></span><span>Filters</span></div>
        <div class="pull-right reset">reset</div>
    </div>
    <div class="sort-by-wrapper clearfix">
        <div class="col-xs-4 sort-by-titl">Sort by</div>
        <div class="col-xs-8 filter-selected-wrapper">
            <span class="filter-selected">Fee </span>
            <span class="arrow-bottom">&#9660;</sapn>
            <span class="arrow-top">&#9650;</span>
        </div>
    </div>
    <div class="clearfix filter-tab-wrapper">
    <div class="filter_options">
    {{#facets}}
    {{#count}}
    
    <div class="facet {{ facet_categorie_name }}" id="{{ facet_categorie_name }}">
        <div class="name">
            {{ facet_categorie_name }}
        </div>
    </div>
    {{/count}}
    {{/facets}}
    </div>

    <div class="filter_options_result">
    {{#facets}}
    {{#count}}
    <div class ="all_results result_{{ facet_categorie_name }}">
            <div class = "scroll-pane" >
                {{#sub_facets}}

                    {{#type.menu}}
                    <div data-tax="{{tax}}" data-name="{{nameattr}}" data-type="menu" class="{{#checked}}checked {{/checked}}sub_facet_mobile menu">
                        <input style="display: none;" data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                        {{name}} {{#print_count}}({{count}}){{/print_count}}
                    </div>
                    {{/type.menu}}

                    {{#type.conjunctive}}
                    <div data-name="{{tax}}" data-type="conjunctive" class="{{#checked}}checked {{/checked}}sub_facet_mobile conjunctive">
                        <input style="display: none;" data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                        {{name}} ({{count}})
                    </div>
                    {{/type.conjunctive}}
                    

                    {{#type.slider}}
                    <div class="algolia-slider algolia-slider-true" data-tax="{{tax}}" data-min="{{min}}" data-max="{{max}}" id="term"></div>
                    <div class="algolia-slider-info">
                        <div class="min" style="float: left;">{{current_min}}</div>
                        <div class="max" style="float: right;">{{current_max}}</div>
                        <div style="clear: both"></div>
                    </div>
                    {{/type.slider}}

                    <div class="options">
                    {{#type.disjunctive}}
                    <div data-name="{{tax}}" data-type="disjunctive" class="{{#checked}}checked {{/checked}}sub_facet_mobile disjunctive">
                        <input data-tax="{{tax}}" {{#checked}}checked{{/checked}} data-name="{{nameattr}}" class="facet_value" type="checkbox" />
                        {{name}} ({{count}})
                    </div>
                    {{/type.disjunctive}}
                    </div>
                    

                {{/sub_facets}}
            </div>
        </div>
        {{/count}}
        {{/facets}}
    </div>
</div>
</div>
<div class="apply">Apply</div>
<?php }?>
</script>


<script type="text/template" id="instant-pagination-template">
<div class="pagination-wrapper{{#facets_count}} with_facets{{/facets_count}} custom-hide-small">
    <div class="text-center">
        <ul class="algolia-pagination">
            <a href="#" data-page="{{prev_page}}">
                <li {{^prev_page}}class="disabled"{{/prev_page}}>
                    &laquo;
                </li>
            </a>

            {{#pages}}
            <a href="#" data-page="{{number}}" return false;>
                <li class="{{#current}}active{{/current}}{{#disabled}}disabled{{/disabled}}">
                    {{ number }}
                </li>
            </a>
            {{/pages}}

            <a href="#" data-page="{{next_page}}">
                <li {{^next_page}}class="disabled"{{/next_page}}>
                    &raquo;
                </li>
            </a>
        </ul>
    </div>
</div>
</script>
