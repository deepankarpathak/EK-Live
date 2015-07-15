/*
Plugin Name: ThemePacific Shortcodes Pro
Plugin URI: http://themepacific.com/wp-plugins/themepacific-shortcode-pro/
Description: A Pro Shortcode Plugin from ThemePacific
Author: Raja CRN, ThemePacific
Author URI: http://themepacific.com
Version: 1.0
Copyright 2013, ThemePacific.
Text Domain: themepacific
@package ThemePacific Shortcodes
@category Core
@author ThemePacific
*/
(function() {	
	tinymce.create('tinymce.plugins.themepacific_sh_mce', {
		init : function(ed, url){
			tinymce.plugins.themepacific_sh_mce.theurl = url;
		},
		createControl : function(tp_my_sh_btn, cm) {
			if ( tp_my_sh_btn == "themepacific_shortcodes_button" ) {
				var tp_sh = this;	
				var tp_my_sh_btn = cm.createSplitButton('themepacific_button', {
	                title: "Insert Shortcode",
					image: tinymce.plugins.themepacific_sh_mce.theurl +"/tp.png",
					icons: false,
	            });
	            tp_my_sh_btn.onRenderMenu.add(function (c, tp_me) {
					
					tp_me.add({title : 'ThemePacific Shortcodes', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
					
					c = tp_me.addMenu({title:"Dropcap"});
					
					tp_sh.render( c, "Red", "redDropcap" );	
					tp_sh.render( c, "Blue", "blueDropcap" );	
					tp_sh.render( c, "Green", "greenDropcap" );	
					tp_sh.render( c, "Yellow", "yellowDropcap" );	
					tp_sh.render( c, "Grey", "greyDropcap" );	
					
					tp_me.addSeparator();		
					
 					// Buttons
					c = tp_me.addMenu({title:"Buttons"});
						
						tp_sh.render( c, "Red", "redButton" );		
						tp_sh.render( c, "Blue", "blueButton" );									
						tp_sh.render( c, "Green", "greenButton" );						
						tp_sh.render( c, "Yellow", "yellowButton" );
						tp_sh.render( c, "Grey", "greyButton" );
						
					tp_me.addSeparator();
					
					// Text Boxes
					c = tp_me.addMenu({title:"Text Boxes"});
						
						tp_sh.render( c, "Black", "blackBox" );		
						tp_sh.render( c, "Red", "redBox" );		
						tp_sh.render( c, "Blue", "blueBox" );									
						tp_sh.render( c, "Green", "greenBox" );						
						tp_sh.render( c, "Yellow", "yellowBox" );
						tp_sh.render( c, "Grey", "greyBox" );
						
					tp_me.addSeparator();
					
					// Highlights
					c = tp_me.addMenu({title:"Highlights"});
						
						tp_sh.render( c, "Red", "redHighlight" );						
						tp_sh.render( c, "Blue", "blueHighlight" );
						tp_sh.render( c, "Green", "greenHighlight" );
 						tp_sh.render( c, "Yellow", "yellowHighlight" );
						tp_sh.render( c, "Grey", "greyHighlight" );
						
					tp_me.addSeparator();
					
					
					// Content Dividers
					c = tp_me.addMenu({title:"Content Dividers"});
					
						tp_sh.render( c, "Solid", "solidDivider" );						
						tp_sh.render( c, "Dotted", "dottedDivider" );
						tp_sh.render( c, "Dashed", "dashedDivider" );
						tp_sh.render( c, "Double", "doubleDivider" );
 
					tp_me.addSeparator();
						// Map
					c = tp_me.addMenu({title:"Map, Testimonial"});
 						 
 						tp_sh.render( c, "Google Map", "googlemap" );
 						tp_sh.render( c, "Testimonial", "testimonial" );
					
					tp_me.addSeparator();
					
					// Tabs,Toggle,Accordion
					c = tp_me.addMenu({title:"Tabs, Toggle, Accordion"});
					
						tp_sh.render( c, "Accordion", "accordion" );
						tp_sh.render( c, "Tabs", "tabs" );
						tp_sh.render( c, "Toggle", "toggle" );
					
					tp_me.addSeparator();
					
					
					// Post Columns
					c = tp_me.addMenu({title:"Post Columns"});
					
						tp_sh.render( c, "One Half", "half" );
						tp_sh.render( c, "One Half Last", "half_last" );
						tp_sh.render( c, "One Third", "third" );
						tp_sh.render( c, "One Third Last", "third_last" );
						tp_sh.render( c, "One Fourth", "fourth" );
						tp_sh.render( c, "One Fourth Last", "fourth_last" );
						tp_sh.render( c, "One Fifth", "fifth" );
						tp_sh.render( c, "One Fifth Last", "fifth_last" );
						tp_sh.render( c, "One Sixth", "sixth" )
						tp_sh.render( c, "One Sixth Last", "sixth_last" )
						
						c.addSeparator();		
								
						tp_sh.render( c, "Two Thirds", "twothird" );
						tp_sh.render( c, "Three Fourths", "threefourth" );
						tp_sh.render( c, "Two Fifths", "twofifth" );
						tp_sh.render( c, "Three Fifths", "threefifth" );
						tp_sh.render( c, "Fourth Fifths", "fourfifth" );
						tp_sh.render( c, "Five Sixths", "fivesixth" );
					
					tp_me.addSeparator();
					
					
				});
	            
	          return tp_my_sh_btn;
			}
			return null;               
		},
		render : function(ed, title, id) {
			ed.add({
				title: title,
				onclick: function () {
					
					// Selected content
					var mceSelected = tinyMCE.activeEditor.selection.getContent();
					
					// Add highlighted content
					if ( mceSelected ) {
						var themepacific_sh_content = mceSelected;
					} else {
						var themepacific_sh_content = 'ThemePacific Shortcode Demo Content';
					}
				
					if(id == "blueDropcap") {
					tinyMCE.activeEditor.selection.setContent('[themepacific_dropcap color="blue"]'+ mceSelected + '[/themepacific_dropcap]');
					}if(id == "redDropcap") {
					tinyMCE.activeEditor.selection.setContent('[themepacific_dropcap color="red"]'+ mceSelected + '[/themepacific_dropcap]');
					}if(id == "greenDropcap") {
					tinyMCE.activeEditor.selection.setContent('[themepacific_dropcap color="green"]'+ mceSelected + '[/themepacific_dropcap]');
					}if(id == "greyDropcap") {
					tinyMCE.activeEditor.selection.setContent('[themepacific_dropcap color="grey"]'+ mceSelected + '[/themepacific_dropcap]');
					}if(id == "yellowDropcap") {
					tinyMCE.activeEditor.selection.setContent('[themepacific_dropcap color="yellow"]'+ mceSelected + '[/themepacific_dropcap]');
					}
			 
					
					
					// Button
					if(id == "blueButton") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_button color="blue" url="http://themepacific.com" title="Visit Site" target="blank" ]' + themepacific_sh_content + '[/themepacific_button]');
					}if(id == "redButton") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_button color="red" url="http://themepacific.com" title="Visit Site" target="blank" ]' + themepacific_sh_content + '[/themepacific_button]');
					}if(id == "greenButton") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_button color="green" url="http://themepacific.com" title="Visit Site" target="blank" ]' + themepacific_sh_content + '[/themepacific_button]');
					}if(id == "yellowButton") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_button color="yellow" url="http://themepacific.com" title="Visit Site" target="blank" ]' + themepacific_sh_content + '[/themepacific_button]');
					}if(id == "greyButton") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_button color="grey" url="http://themepacific.com" title="Visit Site" target="blank" ]' + themepacific_sh_content + '[/themepacific_button]');
					}
					//Testimonial
					if(id == "testimonial") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_testimonial img_url="" author="Raja CRN," pos="Founder, ThemePacific.com"]' + themepacific_sh_content + '[/themepacific_testimonial]');
					}
 					
					// Text Boxes
					if(id == "redBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="red" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}if(id == "blackBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="black" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}
					if(id == "greenBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="green" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}
 					if(id == "blueBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="blue" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}
 					if(id == "yellowBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="yellow" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}
						if(id == "greyBox") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_box color="grey" text_align="left" width="100%"]<br />' + themepacific_sh_content + '<br />[/themepacific_box]');
					}
					
 					// Google Map
					if(id == "googlemap") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_googlemap title="India" location="Bangalore" zoom="10" height=250]');
					}
					
 					
					// Highlight
					if(id == "redHighlight") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_highlight color="red"]' + themepacific_sh_content + '[/themepacific_highlight]');
					}
					if(id == "greenHighlight") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_highlight color="green"]' + themepacific_sh_content + '[/themepacific_highlight]');
					}
					if(id == "greyHighlight") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_highlight color="grey"]' + themepacific_sh_content + '[/themepacific_highlight]');
					}
					if(id == "blueHighlight") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_highlight color="blue"]' + themepacific_sh_content + '[/themepacific_highlight]');
					}
  					if(id == "yellowHighlight") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_highlight color="yellow"]' + themepacific_sh_content + '[/themepacific_highlight]');
					}					
					
					// Accordion
					if(id == "accordion") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_accordion]<br />[themepacific_accordion_section title="Child 1"]<br />Accordion Content<br />[/themepacific_accordion_section]<br />[themepacific_accordion_section title="Child 2"]<br />Accordion Content<br />[/themepacific_accordion_section]<br />[/themepacific_accordion]');
					}
					
 					//Tabs
					if(id == "tabs") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_tabgroup]<br />[themepacific_tab title="First Tab"]<br />First tab content<br />[/themepacific_tab]<br />[themepacific_tab title="Second Tab"]<br />Second Tab Content.<br />[/themepacific_tab]<br />[/themepacific_tabgroup]');
					}
					
 					
					//Toggle
					if(id == "toggle") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_toggle title="Toggle Title"]' + themepacific_sh_content + '[/themepacific_toggle]');
					}
					
					// Columns
					if(id == "half") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-half" pos="first"]<br />' + themepacific_sh_content + '<br />[/themepacific_column]');
					}
					if(id == "half_last") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-half" pos="last"]<br />' + themepacific_sh_content + '<br />[/themepacific_column]');
					}
					if(id == "third") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-third" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "third_last") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-third" pos="last"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fourth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-fourth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fourth_last") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-fourth" pos="last"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fifth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-fifth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fifth_last") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-fifth" pos="last"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "sixth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-sixth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "sixth_last") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="one-sixth" pos="last"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					
					
					if(id == "twothird") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="two-third" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "threefourth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="three-fourth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "twofifth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="two-fifth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "threefifth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="three-fifth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fourfifth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="four-fifth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}
					if(id == "fivesixth") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_column width="five-sixth" pos="first"]' + themepacific_sh_content + '[/themepacific_column]');
					}	
					
 					// Content Divider
					if(id == "solidDivider") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_divider style="solid"]');
					}
					if(id == "dottedDivider") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_divider style="dotted"]');
					}
					if(id == "dashedDivider") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_divider style="dashed"]');
					}
 					if(id == "doubleDivider") {
						tinyMCE.activeEditor.selection.setContent('[themepacific_divider style="double"]');
					}
					
					return false;
				}
			})
		}
	
	});
	tinymce.PluginManager.add("themepacific_shortcodes", tinymce.plugins.themepacific_sh_mce);
})();