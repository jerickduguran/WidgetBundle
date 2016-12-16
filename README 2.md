Mosaic Widget Bundle
=======================

Overview
--------------

Configuration
------------------

All available configuration options are listed below with their default values.
    
    # app/config/mosaic/mosaic_widget.yml
    mosaic_widget:
        editor:
            widget:
                class: mosaic-widget
                items:
                    mosaic_widget.editor.provider.custom:
                        name: Custom Widget Provider
                        settings: { add_type: {type : select, label: "Custom Type",'choices' : { 'type-a' : 'Type A','type-b':'Type B'}},mode: {type : select,label: "Mode",choices : {'private':'Private','public':'Public'}}}