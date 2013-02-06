//fgnass.github.com/spin.js#v1.2.6
!function(e,t,n){function o(e,n){var r=t.createElement(e||"div"),i;for(i in n)r[i]=n[i];return r}function u(e){for(var t=1,n=arguments.length;t<n;t++)e.appendChild(arguments[t]);return e}function f(e,t,n,r){var o=["opacity",t,~~(e*100),n,r].join("-"),u=.01+n/r*100,f=Math.max(1-(1-e)/t*(100-u),e),l=s.substring(0,s.indexOf("Animation")).toLowerCase(),c=l&&"-"+l+"-"||"";return i[o]||(a.insertRule("@"+c+"keyframes "+o+"{"+"0%{opacity:"+f+"}"+u+"%{opacity:"+e+"}"+(u+.01)+"%{opacity:1}"+(u+t)%100+"%{opacity:"+e+"}"+"100%{opacity:"+f+"}"+"}",a.cssRules.length),i[o]=1),o}function l(e,t){var i=e.style,s,o;if(i[t]!==n)return t;t=t.charAt(0).toUpperCase()+t.slice(1);for(o=0;o<r.length;o++){s=r[o]+t;if(i[s]!==n)return s}}function c(e,t){for(var n in t)e.style[l(e,n)||n]=t[n];return e}function h(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var i in r)e[i]===n&&(e[i]=r[i])}return e}function p(e){var t={x:e.offsetLeft,y:e.offsetTop};while(e=e.offsetParent)t.x+=e.offsetLeft,t.y+=e.offsetTop;return t}var r=["webkit","Moz","ms","O"],i={},s,a=function(){var e=o("style",{type:"text/css"});return u(t.getElementsByTagName("head")[0],e),e.sheet||e.styleSheet}(),d={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"auto",left:"auto"},v=function m(e){if(!this.spin)return new m(e);this.opts=h(e||{},m.defaults,d)};v.defaults={},h(v.prototype,{spin:function(e){this.stop();var t=this,n=t.opts,r=t.el=c(o(0,{className:n.className}),{position:"relative",width:0,zIndex:n.zIndex}),i=n.radius+n.length+n.width,u,a;e&&(e.insertBefore(r,e.firstChild||null),a=p(e),u=p(r),c(r,{left:(n.left=="auto"?a.x-u.x+(e.offsetWidth>>1):parseInt(n.left,10)+i)+"px",top:(n.top=="auto"?a.y-u.y+(e.offsetHeight>>1):parseInt(n.top,10)+i)+"px"})),r.setAttribute("aria-role","progressbar"),t.lines(r,t.opts);if(!s){var f=0,l=n.fps,h=l/n.speed,d=(1-n.opacity)/(h*n.trail/100),v=h/n.lines;(function m(){f++;for(var e=n.lines;e;e--){var i=Math.max(1-(f+e*v)%h*d,n.opacity);t.opacity(r,n.lines-e,i,n)}t.timeout=t.el&&setTimeout(m,~~(1e3/l))})()}return t},stop:function(){var e=this.el;return e&&(clearTimeout(this.timeout),e.parentNode&&e.parentNode.removeChild(e),this.el=n),this},lines:function(e,t){function i(e,r){return c(o(),{position:"absolute",width:t.length+t.width+"px",height:t.width+"px",background:e,boxShadow:r,transformOrigin:"left",transform:"rotate("+~~(360/t.lines*n+t.rotate)+"deg) translate("+t.radius+"px"+",0)",borderRadius:(t.corners*t.width>>1)+"px"})}var n=0,r;for(;n<t.lines;n++)r=c(o(),{position:"absolute",top:1+~(t.width/2)+"px",transform:t.hwaccel?"translate3d(0,0,0)":"",opacity:t.opacity,animation:s&&f(t.opacity,t.trail,n,t.lines)+" "+1/t.speed+"s linear infinite"}),t.shadow&&u(r,c(i("#000","0 0 4px #000"),{top:"2px"})),u(e,u(r,i(t.color,"0 0 1px rgba(0,0,0,.1)")));return e},opacity:function(e,t,n){t<e.childNodes.length&&(e.childNodes[t].style.opacity=n)}}),function(){function e(e,t){return o("<"+e+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',t)}var t=c(o("group"),{behavior:"url(#default#VML)"});!l(t,"transform")&&t.adj?(a.addRule(".spin-vml","behavior:url(#default#VML)"),v.prototype.lines=function(t,n){function s(){return c(e("group",{coordsize:i+" "+i,coordorigin:-r+" "+ -r}),{width:i,height:i})}function l(t,i,o){u(a,u(c(s(),{rotation:360/n.lines*t+"deg",left:~~i}),u(c(e("roundrect",{arcsize:n.corners}),{width:r,height:n.width,left:n.radius,top:-n.width>>1,filter:o}),e("fill",{color:n.color,opacity:n.opacity}),e("stroke",{opacity:0}))))}var r=n.length+n.width,i=2*r,o=-(n.width+n.length)*2+"px",a=c(s(),{position:"absolute",top:o,left:o}),f;if(n.shadow)for(f=1;f<=n.lines;f++)l(f,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(f=1;f<=n.lines;f++)l(f);return u(t,a)},v.prototype.opacity=function(e,t,n,r){var i=e.firstChild;r=r.shadow&&r.lines||0,i&&t+r<i.childNodes.length&&(i=i.childNodes[t+r],i=i&&i.firstChild,i=i&&i.firstChild,i&&(i.opacity=n))}):s=l(t,"animation")}(),typeof define=="function"&&define.amd?define(function(){return v}):e.FancyLoadingSpinner=v}(window,document);

/*
 * Bootstrap Image Gallery 2.8
 * https://github.com/blueimp/Bootstrap-Image-Gallery
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, regexp: true */
/*global define, window, document, jQuery */

(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // Register as an anonymous AMD module:
        define([
            'jquery',
            'bootstrap'
        ], factory);
    } else {
        // Browser globals:
        factory(
            window.jQuery
        );
    }
}(function ($) {
    'use strict';
    // Bootstrap Image Gallery is an extension to the Modal dialog of Twitter's
    // Bootstrap toolkit, to ease navigation between a set of gallery images.
    // It features transition effects, fullscreen mode and slideshow functionality.
    $.extend($.fn.modal.defaults, {
        // Delegate to search gallery links from, can be anything that
        // is accepted as parameter for $():
        delegate: document,
        // Selector for gallery links:
        selector: null,
        // The filter for the selected gallery links (e.g. set to ":odd" to
        // filter out label and thumbnail linking twice to the same image):
        filter: '*',
        // The index of the first gallery image to show:
        index: 0,
        // The href of the first gallery image to show (overrides index):
        href: null,
        // The range of images around the current one to preload:
        preloadRange: 2,
        // Offset of image width to viewport width:
        offsetWidth: 100,
        // Offset of image height to viewport height:
        offsetHeight: 200,
        // Shows the next image after the given time in ms (0 = disabled):
        slideshow: 0,
        // Defines the image division for previous/next clicks:
        imageClickDivision: 0.5
    });
    var originalShow = $.fn.modal.Constructor.prototype.show,
        originalHide = $.fn.modal.Constructor.prototype.hide;
    $.extend($.fn.modal.Constructor.prototype, {
        initLinks: function () {
            var $this = this,
                options = this.options,
                selector = options.selector ||
                    'a[data-target=' + options.target + ']';
            this.$links = $(options.delegate).find(selector)
                .filter(options.filter).each(function (index) {
                    if ($this.getUrl(this) === options.href) {
                        options.index = index;
                    }
                });
            if (!this.$links[options.index]) {
                options.index = 0;
            }
        },
        getUrl: function (element) {
            return element.href || $(element).data('href');
        },
        startSlideShow: function () {
            var $this = this;
            if (this.options.slideshow) {
                this._slideShow = window.setTimeout(
                    function () {
                        $this.next();
                    },
                    this.options.slideshow
                );
            }
        },
        stopSlideShow: function () {
            window.clearTimeout(this._slideShow);
        },
        toggleSlideShow: function () {
            var node = this.$element.find('.modal-slideshow');
            if (this.options.slideshow) {
                this.options.slideshow = 0;
                this.stopSlideShow();
            } else {
                this.options.slideshow = node.data('slideshow') || 5000;
                this.startSlideShow();
            }
            node.find('i').toggleClass('icon-play icon-pause');
        },
        preloadImages: function () {
            var options = this.options,
                range = options.index + options.preloadRange + 1,
                link,
                i;
            for (i = options.index - options.preloadRange; i < range; i += 1) {
                link = this.$links[i];
                if (link && i !== options.index) {
                    $('<img>').prop('src', this.getUrl(link));
                }
            }
        },
        loadImage: function () {
            var $this = this,
                modal = this.$element,
                index = this.options.index,
                url = this.getUrl(this.$links[index]),
                oldImg;
            this.abortLoad();
            this.stopSlideShow();
            modal.trigger('beforeLoad');
            // The timeout prevents displaying a loading status,
            // if the image has already been loaded:
            this._loadingTimeout = window.setTimeout(function () {
                modal.addClass('modal-loading');
                var target = modal.find('.modal-body')[0];
                $this.spinner.spin(target);
            }, 100);
            oldImg = modal.find('.modal-image').children().removeClass('in');
            // The timeout allows transition effects to finish:
            window.setTimeout(function () {
                oldImg.remove();
            }, 3000);
            modal.find('.modal-title').text(this.$links[index].title);
            modal.find('.modal-download').prop(
                'href',
                url
            );
            this._loadingImage = (function (url, callback, options) {
                var img = document.createElement('img');
                img.onerror = callback;
                img.onload = function () {
                    callback((function (img, options) {
                        options = options || {};
                        var width = img.width,
                            height = img.height,
                            scale = Math.max(
                                (options.minWidth || width) / width,
                                (options.minHeight || height) / height
                            );
                        if (scale > 1) {
                            width = parseInt(width * scale, 10);
                            height = parseInt(height * scale, 10);
                        }
                        scale = Math.min(
                            (options.maxWidth || width) / width,
                            (options.maxHeight || height) / height
                        );
                        if (scale < 1) {
                            width = parseInt(width * scale, 10);
                            height = parseInt(height * scale, 10);
                        }
                        img.width = width;
                        img.height = height;
                        return img;
                    })(img, options));
                };
                img.src = url;
                return img;
            })(
                url,
                function (img) {
                    $this.img = img;
                    window.clearTimeout($this._loadingTimeout);
                    modal.removeClass('modal-loading');
                    $this.spinner.stop();
                    modal.trigger('loadImage');
                    $this.showImage(img);
                    $this.startSlideShow();
                },
                this._loadImageOptions
            );



            this.preloadImages();
        },
        showImage: function (img) {
            var modal = this.$element,
                transition = $.support.transition && modal.hasClass('fade'),
                method = transition ? modal.animate : modal.css,
                modalImage = modal.find('.modal-image'),
                offsetWidth = Math.max(parseInt(modalImage.css('min-width'), 10) - img.width, 0),
                offsetHeight = Math.max(parseInt(modalImage.css('min-height'), 10) - img.height, 0),
                clone,
                forceReflow;

            modalImage.css({
                width: img.width,
                height: img.height
            });
            $(img).css({
                left: offsetWidth/2,
                top: offsetHeight/2
            });
            modal.find('.modal-title').css({ width: Math.max(img.width, 380) });
            if ($(window).width() > 480) {
                if (transition) {
                    clone = modal.clone().hide().appendTo(document.body);
                }
                method.call(modal.stop(), {
                    'margin-top': -((clone || modal).outerHeight() / 2),
                    'margin-left': -((clone || modal).outerWidth() / 2)
                });
                if (clone) {
                    clone.remove();
                }
            }
            modalImage.append(img);
            forceReflow = img.offsetWidth;
            modal.trigger('display');
            if (transition) {
                if (modal.is(':visible')) {
                    $(img).on(
                        $.support.transition.end,
                        function (e) {
                            // Make sure we don't respond to other transitions events
                            // in the container element, e.g. from button elements:
                            if (e.target === img) {
                                $(img).off($.support.transition.end);
                                modal.trigger('displayed');
                            }
                        }
                    ).addClass('in');
                } else {
                    $(img).addClass('in');
                    modal.one('shown', function () {
                        modal.trigger('displayed');
                    });
                }
            } else {
                $(img).addClass('in');
                modal.trigger('displayed');
            }
        },
        abortLoad: function () {
            if (this._loadingImage) {
                this._loadingImage.onload = this._loadingImage.onerror = null;
            }
            window.clearTimeout(this._loadingTimeout);
        },
        prev: function () {
            var options = this.options;
            options.index -= 1;
            if (options.index < 0) {
                options.index = this.$links.length - 1;
            }
            this.loadImage();
        },
        next: function () {
            var options = this.options;
            options.index += 1;
            if (options.index > this.$links.length - 1) {
                options.index = 0;
            }
            this.loadImage();
        },
        keyHandler: function (e) {
            switch (e.which) {
            case 37: // left
            case 38: // up
                e.preventDefault();
                this.prev();
                break;
            case 39: // right
            case 40: // down
                e.preventDefault();
                this.next();
                break;
            }
        },
        initGalleryEvents: function () {
            var $this = this,
                modal = this.$element,
                ready = false,
                touch = false,
                timerin, timerout;

            modal.find('.modal-image').on('touchend.modal-gallery', function(e){
                clearTimeout(timerin);
                clearTimeout(timerout);
                modal.addClass('modal-hover');
                timerin = setTimeout(function(){
                    ready = true;

                    clearTimeout(timerout);
                    timerout = setTimeout(function(){
                        modal.removeClass('modal-hover');
                        ready = false;
                    }, 3000);
                }, 400);
                touch = true;
            });
            modal.find('.modal-image').on('mouseenter.modal-gallery', function(e){
                if(touch) return;
                clearTimeout(timerin);
                modal.addClass('modal-hover');
                timerin = setTimeout(function(){
                    ready = true;
                }, 400);
            });
            modal.find('.modal-image').on('mouseleave.modal-gallery', function(e){
                if(touch) return;
                modal.removeClass('modal-hover');
                ready = false;
            });

            modal.find('.modal-image').on('click.modal-gallery', function (e) {
                if(!ready) return;

                var modalImage = $(this);
                if ($this.$links.length === 1) {
                    $this.hide();
                } else {
                    if ((e.pageX - modalImage.offset().left) / modalImage.width() <
                            $this.options.imageClickDivision) {
                        $this.prev(e);
                    } else {
                        $this.next(e);
                    }
                }
            });
            modal.find('.modal-prev').on('click.modal-gallery', function (e) {
                $this.prev(e);
            });
            modal.find('.modal-next').on('click.modal-gallery', function (e) {
                $this.next(e);
            });
            modal.find('.modal-slideshow').on('click.modal-gallery', function (e) {
                $this.toggleSlideShow(e);
            });
            $(document)
                .on('keydown.modal-gallery', function (e) {
                    $this.keyHandler(e);
                });
        },
        destroyGalleryEvents: function () {
            var modal = this.$element;
            this.abortLoad();
            this.stopSlideShow();
            modal
                .removeClass('modal-hover')
                .off('touchend.modal-gallery mouseenter.modal-gallery mouseleave.modal-gallery');
            modal.find('.modal-image, .modal-prev, .modal-next, .modal-slideshow')
                .off('click.modal-gallery');
            $(document)
                .off('keydown.modal-gallery');
        },
        show: function () {
            if (!this.isShown && this.$element.hasClass('modal-gallery')) {
                var modal = this.$element,
                    options = this.options,
                    windowWidth = $(window).width(),
                    windowHeight = $(window).height();
                if (modal.hasClass('modal-fullscreen')) {
                    this._loadImageOptions = {
                        maxWidth: windowWidth-20,
                        maxHeight: windowHeight-20
                    };
                    if (modal.hasClass('modal-fullscreen-stretch')) {
                        this._loadImageOptions.minWidth = windowWidth;
                        this._loadImageOptions.minHeight = windowHeight;
                    }
                } else {
                    this._loadImageOptions = {
                        maxWidth: windowWidth - options.offsetWidth,
                        maxHeight: windowHeight - options.offsetHeight
                    };
                }
                if (windowWidth > 480) {
                    modal.css({
                        'margin-top': -(modal.outerHeight() / 2),
                        'margin-left': -(modal.outerWidth() / 2)
                    });
                }

                var opts = {
                    lines: 12, // The number of lines to draw
                    length: 10, // The length of each line
                    width: 4, // The line thickness
                    radius: 10, // The radius of the inner circle
                    corners: 1, // Corner roundness (0..1)
                    rotate: 0, // The rotation offset
                    color: '#FFF', // #rgb or #rrggbb
                    speed: 1, // Rounds per second
                    trail: 60, // Afterglow percentage
                    shadow: true, // Whether to render a shadow
                    hwaccel: false, // Whether to use hardware acceleration
                    className: 'spinner', // The CSS class to assign to the spinner
                    zIndex: 2e9, // The z-index (defaults to 2000000000)
                    top: 'auto', // Top position relative to parent in px
                    left: 'auto' // Left position relative to parent in px
                };
                this.spinner = new FancyLoadingSpinner(opts);

                this.initGalleryEvents();
                this.initLinks();
                if (this.$links.length) {
                    modal.find('.modal-slideshow, .modal-prev, .modal-next')
                        .toggle(this.$links.length !== 1);
                    modal.toggleClass(
                        'modal-single',
                        this.$links.length === 1
                    );
                    this.loadImage();
                }
            }
            originalShow.apply(this, arguments);
        },
        hide: function () {
            if (this.isShown && this.$element.hasClass('modal-gallery')) {
                this.options.delegate = document;
                this.options.href = null;
                this.destroyGalleryEvents();
            }
            originalHide.apply(this, arguments);
        }
    });
    $(function () {
        $(document.body).on(
            'click.modal-gallery.data-api',
            '[data-toggle="modal-gallery"]',
            function (e) {
                var $this = $(this),
                    options = $this.data(),
                    modal = $(options.target),
                    data = modal.data('modal'),
                    link;
                if (!data) {
                    options = $.extend(modal.data(), options);
                }
                if (!options.selector) {
                    options.selector = 'a[rel=gallery]';
                }
                link = $(e.target).closest(options.selector);
                if (link.length && modal.length) {
                    e.preventDefault();
                    options.href = link.prop('href') || link.data('href');
                    options.delegate = link[0] !== this ? this : document;
                    if (data) {
                        $.extend(data.options, options);
                    }
                    modal.modal(options);
                }
            }
        );
    });
}));