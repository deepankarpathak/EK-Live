(function() {
tinymce.PluginManager.requireLangPack('pullquote');
   tinymce.create('tinymce.plugins.pullquote', {
      init : function(ed, url) {
         ed.addButton('pullquote', {
            title : 'pullquote.title',
            image : url+'/pullquotebutton.png',
            onclick : function() {
               var text = prompt(ed.getLang('pullquote.pullquote_headline', 0), ed.getLang('pullquote.pullquote_text', 0));

               if (text != null && text != ''){
                    ed.execCommand('mceInsertContent', false, '<div class="pullquote">'+text+'</div>');
               }
               else{
                     ed.execCommand('mceInsertContent', false, '');
               }
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Pullquote",
            author : 'Sami Keijonen',
            authorurl : 'http://foxnet.fi',
            infourl : 'http://foxnet.fi',
            version : "0.1"
         };
      }
   });
   tinymce.PluginManager.add('pullquote', tinymce.plugins.pullquote);
})();
