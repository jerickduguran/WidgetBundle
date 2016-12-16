( function() {
CKEDITOR.plugins.add( 'vimeo', {
            requires: 'widget',  
            lang: [ 'en'], 
            icons: 'vimeo',
            init: function( editor ) { 
               CKEDITOR.config.baseFloatZIndex = 1035;
               CKEDITOR.dialog.add('vimeo', function( instance ) {                     
				var video; 
				return {
					title : editor.lang.vimeo.title,
					minWidth : 500,
					minHeight : 200,
					contents :
						[{
							id : 'vimeoPlugin',
							expand : true,
							elements :
								[{
									type : 'hbox',
									widths : [ '70%', '15%', '15%' ],
									children :
									[
										{
                                                                                    id : 'txtVideoId',
                                                                                    type : 'text',
                                                                                    label : editor.lang.vimeo.txtVideoId, 
                                                                                    validate : function ()
                                                                                    { 
                                                                                        if ( !this.getValue() )
                                                                                        {
                                                                                            alert( editor.lang.vimeo.noCode );
                                                                                            return false;
                                                                                        }
                                                                                        else{
                                                                                            video = this.getValue();  
                                                                                        } 
                                                                                    },
                                                                                    setup: function( widget ) {
                                                                                        this.setValue( widget.data.txtVideoId );
                                                                                    },
                                                                                    commit: function( widget ) {
                                                                                        widget.setData( 'txtVideoId', this.getValue() );
                                                                                    }
										},
										{
											type : 'text',
											id : 'txtWidth',
											width : '60px',
											label : editor.lang.vimeo.txtWidth,
											'default' : editor.config.vimeo_width != null ? editor.config.vimeo_width : '640',
											validate : function ()
											{
                                                                                            if ( this.getValue() )
                                                                                            {
                                                                                                var width = parseInt ( this.getValue() ) || 0;

                                                                                                if ( width === 0 )
                                                                                                {
                                                                                                        alert( editor.lang.vimeo.invalidWidth );
                                                                                                        return false;
                                                                                                }
                                                                                            }
                                                                                            else {
                                                                                                alert( editor.lang.vimeo.noWidth );
                                                                                                return false;
                                                                                            }
											},
                                                                                        setup: function( widget ) {
                                                                                            this.setValue( widget.data.txtWidth );
                                                                                        },
                                                                                        commit: function( widget ) {
                                                                                            widget.setData( 'txtWidth', this.getValue() );
                                                                                        }
										},
										{
											type : 'text',
											id : 'txtHeight',
											width : '60px',
											label : editor.lang.vimeo.txtHeight,
											'default' : editor.config.vimeo_height != null ? editor.config.vimeo_height : '360',
											validate : function ()
											{
												if ( this.getValue() )
												{
                                                                                                    var height = parseInt ( this.getValue() ) || 0;

                                                                                                    if ( height === 0 )
                                                                                                    {
                                                                                                        alert( editor.lang.vimeo.invalidHeight );
                                                                                                        return false;
                                                                                                    }
												}
												else {
                                                                                                    alert( editor.lang.vimeo.noHeight );
                                                                                                    return false;
												}
											},
                                                                                        setup: function( widget ) {
                                                                                            this.setValue( widget.data.txtHeight );
                                                                                        },
                                                                                        commit: function( widget ) {
                                                                                            widget.setData( 'txtHeight', this.getValue() );
                                                                                        }
										}
									]
								},
                                                                {
									type : 'hbox',
									widths : [ '100%' ],
									children :
										[
											{
												id : 'txtColor',
												type : 'text',
                                                                                                width : '90px',
												label : editor.lang.vimeo.txtColor,
												'default' : 'FF0000',
                                                                                                setup: function( widget ) {
                                                                                                    this.setValue( widget.data.txtColor );
                                                                                                },
                                                                                                commit: function( widget ) {
                                                                                                    widget.setData( 'txtColor', this.getValue() );
                                                                                                }
											},
                                                                                        {
												id : 'chkPortrait',
												type : 'checkbox',
												label : editor.lang.vimeo.txtPortrait,
												'default' : editor.config.txtPortrait != null ? editor.config.txtPortrait : false,
                                                                                                setup: function( widget ) {
                                                                                                    this.setValue( widget.data.chkPortrait );
                                                                                                },
                                                                                                commit: function( widget ) {
                                                                                                    widget.setData( 'chkPortrait', this.getValue() );
                                                                                                }
											},
                                                                                        {
												id : 'chkTitle',
												type : 'checkbox',
												label : editor.lang.vimeo.txtTitle,
												'default' : editor.config.txtTitle != null ? editor.config.txtTitle : false,
                                                                                                setup: function( widget ) {
                                                                                                    this.setValue( widget.data.chkTitle );
                                                                                                },
                                                                                                commit: function( widget ) {
                                                                                                    widget.setData( 'chkTitle', this.getValue() );
                                                                                                }
											},
                                                                                        {
												id : 'chkByline',
												type : 'checkbox',
												label : editor.lang.vimeo.txtByline,
												'default' : editor.config.txtByline != null ? editor.config.txtByline : false,
                                                                                                setup: function( widget ) {
                                                                                                    this.setValue( widget.data.chkByline );
                                                                                                },
                                                                                                commit: function( widget ) {
                                                                                                    widget.setData( 'chkByline', this.getValue() );
                                                                                                }
											}
										]
								}, 
								{
									type : 'hbox',
									widths : [ '55%', '45%' ],
									children :
									[
										{
											id : 'chkLoopVideo',
											type : 'checkbox',
											'default' : editor.config.vimeo_chkLoopVideo != null ? editor.config.vimeo_chkLoopVideo : true,
											label : editor.lang.vimeo.chkLoopVideo,
                                                                                        setup: function( widget ) {
                                                                                            this.setValue( widget.data.chkLoopVideo );
                                                                                        },
                                                                                        commit: function( widget ) {
                                                                                            widget.setData( 'chkLoopVideo', this.getValue() );
                                                                                        }
										}
									]
								},
								{
									type : 'hbox',
									widths : [ '55%', '45%' ],
									children :
									[ 
										{
											id : 'chkAutoplay',
											type : 'checkbox',
											'default' : editor.config.vimeo_autoplay != null ? editor.config.vimeo_autoplay : false,
											label : editor.lang.vimeo.chkAutoplay,
                                                                                        setup: function( widget ) {
                                                                                            this.setValue( widget.data.chkAutoplay );
                                                                                        },
                                                                                        commit: function( widget ) {
                                                                                            widget.setData( 'chkAutoplay', this.getValue() );
                                                                                        }
										}
									]
								},
								{
                                                                    type : 'hbox',
                                                                    widths : [ '55%', '45%' ],
                                                                    children :
                                                                    [ 
                                                                        {
                                                                            id : 'chkAllowFullscreen',
                                                                            type : 'checkbox',
                                                                            'default' : editor.config.vimeo_allowFullscreen != null ? editor.config.vimeo_allowFullscreen : false,
                                                                            label : editor.lang.vimeo.chkAllowFullscreen,
                                                                            setup: function( widget ) {
                                                                                this.setValue( widget.data.chkAllowFullscreen );
                                                                            },
                                                                            commit: function( widget ) {
                                                                                widget.setData( 'chkAllowFullscreen', this.getValue() );
                                                                            }
                                                                        },
                                                                        {
                                                                                id : 'chkResponsive',
                                                                                type : 'checkbox',
                                                                                label : editor.lang.vimeo.txtResponsive,
                                                                                'default' : editor.config.vimeo_responsive != null ? editor.config.vimeo_responsive : false,
                                                                                setup: function( widget ) {
                                                                                    this.setValue( widget.data.chkResponsive );
                                                                                },
                                                                                commit: function( widget ) {
                                                                                    widget.setData( 'chkResponsive', this.getValue() );
                                                                                }
                                                                        }
                                                                    ]
								},
								{
                                                                    type : 'hbox',
                                                                    widths : [ '55%', '45%'],
                                                                    children :
                                                                    [ 
                                                                        {
                                                                            id: 'empty',
                                                                            type: 'html',
                                                                            html: ''
                                                                        }
                                                                    ]
								}
							]
						}
					],
					onOk: function()
					{
						var content = '';
						var responsiveStyle='';
 
                                                var url    = '//', params = [];
                                                var width  = this.getValueOf( 'vimeoPlugin', 'txtWidth' );
                                                var height = this.getValueOf( 'vimeoPlugin', 'txtHeight' );
                                                var color  = this.getValueOf( 'vimeoPlugin', 'txtColor' );
                                                        
                                                url += "player.vimeo.com/video";
                                                url += '/' + video;
                                                var data_attributes = "data-video-id='"+video+"' ";
                                                
                                                data_attributes  += "data-width='"+width+"' ";
                                                data_attributes  += "data-height='"+height+"' ";
                                                data_attributes  += "data-color='"+color+"' ";
                                                
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkPortrait' ).getValue() === true )
                                                {
                                                     params.push('portrait=1');
                                                     data_attributes += "data-portrait='1' ";
                                                }else{
                                                     params.push('portrait=0'); 
                                                } 
                                                
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkTitle' ).getValue() === true )
                                                {
                                                     params.push('title=1');
                                                     data_attributes += "data-title=1 ";
                                                }else{
                                                     params.push('title=0'); 
                                                } 
                                                
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkByline' ).getValue() === true )
                                                {
                                                     params.push('byline=1');     
                                                     data_attributes += "data-byline='1' "; 
                                                }else{
                                                     params.push('byline=0'); 
                                                } 
                                                
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkLoopVideo' ).getValue() === true )
                                                {
                                                     params.push('loop=1');
                                                     data_attributes += "data-loop='1' "; 
                                                }else{
                                                     params.push('loop=0'); 
                                                } 

                                                if ( this.getContentElement( 'vimeoPlugin', 'chkAutoplay' ).getValue() === true )
                                                {   
                                                     params.push('autoplay=1');
                                                     data_attributes += "data-autoplay='1' "; 
                                                } else{
                                                     params.push('autoplay=0'); 
                                                } 
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkAllowFullscreen').getValue() === true ) { 
                                                     data_attributes += "data-allow-fullscreen='1' ";  
                                                }

                                                if ( params.length > 0 )
                                                {
                                                        url = url + '?' + params.join( '&' );
                                                }
                                                var responsiveStyle = ""; 
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkResponsive').getValue() === true ) { 
                                                     responsiveStyle = 'style="width: 100%;height: 100%;"';
                                                     data_attributes += "data-responsive='1' "; 
                                                }
                                                var fullScreenAttrs = "";
                                                if ( this.getContentElement( 'vimeoPlugin', 'chkAllowFullscreen').getValue() === true ) {
                                                     fullScreenAttrs = "webkitallowfullscreen mozallowfullscreen allowfullscreen";
                                                }  
                                                content += '<div class="mosaic-vimeo-wrapper" '+data_attributes+'">';
                                                content += '<iframe '+data_attributes+' '+fullScreenAttrs+' class="vimeo-embed-frame" width="' + width + '" height="' + height + '" src="' + url + '" ' + responsiveStyle;
                                                content += 'frameborder="0"></iframe>';   
                                                content += '<div>';   
						
						var element   = CKEDITOR.dom.element.createFromHtml( content );                                                
                                                var elementId = "video-element-" + Math.floor((Math.random() * 999999) + 9999);

                                                element.setAttribute("id", elementId);   
                                                var instanceWidget = this.getParentEditor();
                                                    instanceWidget.insertElement(element); 
                                                var data = instanceWidget.getData();   
                                                setTimeout(function(){instanceWidget.setData(data);},0); 
					}
				};
                } ); 
                
                
                editor.widgets.add( 'Vimeo', {
                    button         : 'Vimeo', 
                    template       : '<div class="mosaic-vimeo-wrapper"></div>',  
                    allowedContent : 'div{*}; iframe{*}[!width,!height,!src,!frameborder,!allowfullscreen]; object param[*]', 
                    requiredContent: 'iframe(vimeo-embed-frame)', 
                    dialog         : 'vimeo', 
                    upcast         : function( element ) {  
                        return element.name == 'div' && element.attributes.class == 'mosaic-vimeo-wrapper';
                    },
                    init: function() {                           
                        this.setData("txtVideoId",this.element.data('video-id'));  
                        this.setData("chkPortrait",this.element.data('portrait') == 'null' ? null : this.element.data('portrait'));  
                        this.setData("chkTitle",this.element.data('title') == 'null' ? null : this.element.data('title'));  
                        this.setData("chkByline",this.element.data('byline') == 'null' ? null : this.element.data('byline'));  
                        this.setData("chkAutoplay",this.element.data('autoplay') == 'null' ? null : this.element.data('autoplay'));    
                        this.setData("chkLoopVideo",this.element.data('loop') == 'null' ? null : this.element.data('loop'));  
                        this.setData("txtWidth",this.element.data('width'));  
                        this.setData("txtHeight",this.element.data('height'));  
                        this.setData("txtColor",this.element.data('color') == '' ? 'FF0000' : this.element.data('color'));  
                        this.setData("chkAllowFullscreen",this.element.data('allow-fullscreen') == 'null' ? null : this.element.data('allow-fullscreen'));  
                        this.setData("chkResponsive",this.element.data('responsive') == 'null' ? 'FF0000' : this.element.data('responsive'));  
                    }, 
                    data: function() {   
                         this.element.data("video-id",this.data.txtVideoId);  
                         this.element.data("portrait",this.data.chkPortrait);  
                         this.element.data("title",this.data.chkTitle);  
                         this.element.data("autoplay",this.data.chkAutoplay);  
                         this.element.data("byline",this.data.chkByline);  
                         this.element.data("loop",this.data.chkLoopVideo);  
                         this.element.data("width",this.data.txtWidth);  
                         this.element.data("height",this.data.txtHeight);  
                         this.element.data("color",this.data.txtColor);  
                         this.element.data("allow-fullscreen",this.data.chkAllowFullscreen);   
                         this.element.data("responsive",this.data.chkResponsive);   
                    }
                } );
            }
        } ); 
})();