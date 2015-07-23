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

<script type="text/javascript">
       $('button').on('click',function(e) {
    if ($(this).hasClass('grid')) {
        $('#view li').removeClass('list').addClass('grid');
    }
    else if($(this).hasClass('list')) {
        $('#view li').removeClass('grid').addClass('list');
    }
});
</script>

<script type="text/template" id="instant-content-template">
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12  hits{{#facets_count}} with_facets{{/facets_count}}">
        {{#hits.length}}
        <div class="infos">
            <div style="float: left">
                 result{{^nbHits_one}}s{{/nbHits_one}} for <strong>{{query}}</strong> ({{nbHits}} courses)
            </div>

            {{#sorting_indices.length}}
            <div style="float: right; margin-right: 10px; font-size: 15px">
                Order by
				<div class="select-wrapper">
                	<select id="index_to_use" class="custom" style="height: 15px; width: 155px; font-size: 12px">
                   		<option {{#sortSelected}}{{relevance_index_name}}{{/sortSelected}} value="{{relevance_index_name}}">Default Sorting</option>
                    	{{#sorting_indices}}
                    	<option {{#sortSelected}}{{index_name}}{{/sortSelected}} value="{{index_name}}">{{label}}</option>
                    	{{/sorting_indices}}
                	</select>
				</div>
            </div>
            {{/sorting_indices.length}}
			<div class="button-123 right hidden-xs">
                <button class="grid" onclick=" $('#view li').removeClass('list').addClass('grid'); $('#view .list-images').removeClass('list-images').addClass('grid-images'); $('#view .result-sub-content-list').removeClass('result-sub-content-list').addClass('result-sub-content-grid'); $('#view .price-list').removeClass('price-list').addClass('price-grid');" style="height: 30px; padding-left: 10px; width: 50px; background-color: transparent;"><img src="images/grid_view.png"/></button>
                <button class="list" onclick="$('#view li').removeClass('grid').addClass('list'); $('#view .grid-images').removeClass('grid-images').addClass('list-images'); $('#view .result-sub-content-grid').removeClass('result-sub-content-grid').addClass('result-sub-content-list'); $('#view .price-grid').removeClass('price-grid').addClass('price-list');" style="height: 30px; padding-left: 10px; width: 50px; background-color: transparent;"><img src="images/list_view.png"/></button>
            </div>
            <div style="clear: both;"></div>
        </div>
        {{/hits.length}}

        <ul id="view" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
            {{#hits}}
			<li class="col-lg-3 col-md-4 col-sm-6 col-xs-12 grid">
            	<div onclick="window.location='{{permalink}}'">
                    <div class="result">
                        <div class="result-content clearfix">          
                            <div class="result-sub-content-grid clearfix">
                                <div class="result-thumbnail">
                                    <div class="grid-images">
                                        {{#featureImage}}
                                            <img src="{{{ featureImage.file }}}" />

                                        {{/featureImage}}
                                    </div>
                                    {{^featureImage}}
                                    <div style="height: 50px;"></div>
                                {{/featureImage}}
                                </div>
                                <div class="result-excerpt clearfix">
									<h4 class="result-title">
                                    	{{{ _highlightResult.title.value }}} - {{pa_specialization}}
                                	</h4>
                                    <div class="institute">{{university}}</div>
                                    <div class="mode"> Study Content: {{pa_exam-mode}}</div>
									<div class="duration">{{pa_duration}}</div>
                                    <div class="price-grid"> ₹ {{_price}}</div>
									{{#pa_referral-cashback.length}}
									<div class="referral"><span class="cashback">Cashback</span> ₹ {{pa_referral-cashback}}</div>
									{{/pa_referral-cashback.length}}
                                </div>
                            </div>
                        </div>
                    </div>               
            	</div>
			</li>
            {{/hits}}
        </ul>

        
        <div style="clear: both;"></div>
    </div>
{{^hits.length}}
		<div class="row">
        	<div class="col-lg-12 col-md-12">Sorry, You’re looking for <strong>{{query}}</strong> which isn't here. However, we have wide range of courses which will help you enhance your skills.<br/><br/>For Certificates, <a href="http://192.168.2.135/edukart/#q=certificate&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22
">Click here</a>  |   For Entrance Coaching, <a href="http://192.168.2.135/edukart/#q=entrance&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here.</a>  |  For School Education, <a href="http://192.168.2.135/edukart/#q=class&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a>   |   For Degree Programs, <a href="http://192.168.2.135/edukart/#q=degree&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a>  |   For Diplomas, <a href="http://192.168.2.135/edukart/#q=diploma&page=0&refinements=%5B%5D&numerics_refinements=%7B%7D&index_name=%22tryall%22">Click here</a><br/>
        	</div>
		</div>
        {{/hits.length}}
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
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 facets{{#count}} with_facets{{/count}}">
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