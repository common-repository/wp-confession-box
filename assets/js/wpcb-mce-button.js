(function() {
        tinymce.PluginManager.add('wpcb_mce_button', function( editor, url ) {
           editor.addButton( 'wpcb_mce_button', {
                 text: 'Confession Box Shortcodes',
                 icon: true,
                 type: 'menubutton',
                 menu: [
                       {
                        text: 'Confession From',
                        onclick: function() {
                           editor.insertContent('[wp-confession-form]');
                                  }
                        },
                       {
                        text: 'Confession Box',
                        onclick: function() {
                           editor.insertContent('[wp-confession-box]');
                                 }
                       }
                       ]
              });
        });
})();