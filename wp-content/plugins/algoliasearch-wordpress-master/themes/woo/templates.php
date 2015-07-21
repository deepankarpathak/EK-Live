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

<script type="text/javascript" >
       $('button').on('click',function(e) {
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
});
</script>

<script type="text/template" id="instant-content-template">
    <div class="hits{{#facets_count}} with_facets{{/facets_count}}">
        {{#hits.length}}
        <div class="infos">
            <div style="float: left">
                {{nbHits}} result{{^nbHits_one}}s{{/nbHits_one}} found matching "<strong>{{query}}</strong>"
            </div>

            {{#sorting_indices.length}}
            <div style="float: right; margin-right: 10px; font-size: 15px">
                Order by
                <select id="index_to_use" style="height: 15px; width: 155px; font-size: 12px">
                    <option {{#sortSelected}}{{relevance_index_name}}{{/sortSelected}} value="{{relevance_index_name}}">Default Sorting</option>
                    {{#sorting_indices}}
                    <option {{#sortSelected}}{{index_name}}{{/sortSelected}} value="{{index_name}}">{{label}}</option>
                    {{/sorting_indices}}
                </select>
            </div>
            {{/sorting_indices.length}}
            <div style="clear: both;"></div>
        </div>
        {{/hits.length}}

        <div class="container">
            <div class="button-123" style="height: 30px; padding-left: 770px;">
                <button class="grid" onclick=" $('#view ul').removeClass('list').addClass('grid'); $('#view .list-images').removeClass('list-images').addClass('grid-images'); $('#view .result-sub-content-list').removeClass('result-sub-content-list').addClass('result-sub-content-grid'); $('#view .price-list').removeClass('price-list').addClass('price-grid');" style="height: 30px; padding-left: 10px; width: 50px; background-color: transparent;"><img src="images/grid_view.png"/></button>
                <button class="list" onclick="$('#view ul').removeClass('grid').addClass('list'); $('#view .grid-images').removeClass('grid-images').addClass('list-images'); $('#view .result-sub-content-grid').removeClass('result-sub-content-grid').addClass('result-sub-content-list'); $('#view .price-grid').removeClass('price-grid').addClass('price-list');" style="height: 30px; padding-left: 10px; width: 50px; background-color: transparent;"><img src="images/list_view.png"/></button>
            </div>
        </div>

        <div id="view">
            {{#hits}}
            <a href="{{permalink}}">
                <ul class="grid">
                    <div class="result">
                        <div class="result-content">
                            <div>
                                <h4 class="result-title" style="text-align: left; padding-left: 30px; padding-top: 5px; word-wrap: break-word;">
                                    {{{ _highlightResult.title.value }}} - {{pa_specialization}}
                                </h4>
                            </div>
                            <div class="result-sub-content-grid">
                                <div class="result-thumbnail">
                                    <div class="grid-images">
                                        {{#featureImage}}
                                            <img height=50 src="{{{ featureImage.file }}}" />

                                        {{/featureImage}}
                                    </div>
                                    {{^featureImage}}
                                    <div style="height: 50px;"></div>
                                {{/featureImage}}
                                </div>
                                <div class="result-excerpt">
                                    <div class="institute"> Institute: {{university}}</div>
                                    <div class="mode"> Study Content: {{pa_exam-mode}}</div>
                                    <div class="provider"> Provider: {{shop_vendor}}</div>
                                    <div class="price-grid" style="float: left;"> ₹ {{_price}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
            </a>
            {{/hits}}
        </div>

        {{^hits.length}}
        <div class="infos">
            No results found matching "<strong>{{query}}</strong>".
        </div>
        {{/hits.length}}
        <div style="clear: both;"></div>
    </div>
</script>

<script type="text/javascript" >
$('button').on('click',function(e) {
    if ($(this).hasClass('grid')) {
        alert("GRID");
        $('#instant-content-template ul').removeClass('list').addClass('grid');
    }
    else if($(this).hasClass('list')) {
        alert("LIST");
        $('#instant-content-template ul').removeClass('grid').addClass('list');
    }
    else
    {
        alert("Here!");
    }
});
</script>
<!-- <script type="text/template" id="instant-facets-template">
<div class="facets{{#count}} with_facets{{/count}}">
    {{#facets}}
    {{#count}}
    <div class="facet">
        <div class="name">
            {{ facet_categorie_name }}
        </div>
        <div>
        {{#sub_facets}}

         {{#type.slider}}
                <div class="algolia-slider algolia-slider-true" data-tax="{{tax}}" data-min="{{min}}" data-max="{{max}}"></div>
                <div class="algolia-slider-info">
                    <div class="min" style="float: left;">{{current_min}}</div>
                    <div class="max" style="float: right;">{{current_max}}</div>
                    <div style="clear: both"></div>
                </div>
                {{/type.slider}}

            {{/sub_facets}}
        </div>
    </div>
</div>
    {{/count}}
    {{/facets}}
</div>
</script> -->

<script type="text/template" id="instant-facets-template">
<div class="facets{{#count}} with_facets{{/count}}">
    <button onclick="$('.options input:checkbox').prop('checked', false);">Clear All </button>
    {{#facets}}
    {{#count}}
    <div class="facet">
        <div class="name">
            {{ facet_categorie_name }}
        </div>
        <div>
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
                <div data-name="{{tax}}" data-type="disjunctive" class="{{#checked}}checked {{/checked}}sub_facet disjunctive" style="height: 15px;">
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
</script>

<script type="text/template" id="instant-pagination-template">
<div class="pagination-wrapper{{#facets_count}} with_facets{{/facets_count}}">
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
