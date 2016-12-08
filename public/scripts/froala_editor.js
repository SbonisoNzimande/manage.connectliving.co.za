/*!
 * froala_editor v1.1.5 (http://editor.froala.com)
 * Copyright 2014-2014 Froala
 */
if ("undefined" == typeof jQuery) throw new Error("Froala requires jQuery");
! function(a) {
    "use strict";
    var b = function(c, d) {
        this.options = a.extend({}, b.DEFAULTS, a(c).data(), "object" == typeof d && d), this.browser = b.browser(), this.disabledList = [], this._id = ++b.count, this.init(c)
    };
    b.count = 0, b.VALID_NODES = ["P", "PRE", "BLOCKQUOTE", "H1", "H2", "H3", "H4", "H5", "H6", "DIV", "LI"], b.COLORS = ["#000000", "#444444", "#666666", "#999999", "#CCCCCC", "#EEEEEE", "#F3F3F3", "#FFFFFF", "#FF0000", "#FF9900", "#FFFF00", "#00FF00", "#00FFFF", "#0000FF", "#9900FF", "#FF00FF", "#F4CCCC", "#FCE5CD", "#FFF2CC", "#D9EAD3", "#D0E0E3", "#CFE2F3", "#D9D2E9", "#EAD1DC", "#EA9999", "#F9CB9C", "#FFE599", "#B6D7A8", "#A2C4C9", "#9FC5E8", "#B4A7D6", "#D5A6BD", "#E06666", "#F6B26B", "#FFD966", "#93C47D", "#76A5AF", "#6FA8DC", "#8E7CC3", "#C27BA0", "#CC0000", "#E69138", "#F1C232", "#6AA84F", "#45818E", "#3D85C6", "#674EA7", "#A64D79", "#990000", "#B45F06", "#BF9000", "#38771D", "#134F5C", "#0B5394", "#351C75", "#741B47", "#660000", "#783F04", "#7F6000", "#274E13", "#0C343D", "#073763", "#201211", "#4C1130"], b.image_commands = {
        floatImageLeft: {
            title: "Float Left",
            icon: {
                type: "font",
                value: "fa fa-align-left"
            }
        },
        floatImageNone: {
            title: "Float None",
            icon: {
                type: "font",
                value: "fa fa-align-justify"
            }
        },
        floatImageRight: {
            title: "Float Right",
            icon: {
                type: "font",
                value: "fa fa-align-right"
            }
        },
        linkImage: {
            title: "Insert Link",
            icon: {
                type: "font",
                value: "fa fa-link"
            }
        },
        replaceImage: {
            title: "Replace Image",
            icon: {
                type: "font",
                value: "fa fa-exchange"
            }
        },
        removeImage: {
            title: "Remove Image",
            icon: {
                type: "font",
                value: "fa fa-trash-o"
            }
        }
    }, b.commands = {
        bold: {
            title: "Bold",
            icon: "fa fa-bold",
            shortcut: "(Ctrl + B)"
        },
        italic: {
            title: "Italic",
            icon: "fa fa-italic",
            shortcut: "(Ctrl + I)"
        },
        underline: {
            cmd: "underline",
            title: "Underline",
            icon: "fa fa-underline",
            shortcut: "(Ctrl + U)"
        },
        strikeThrough: {
            title: "Strikethrough",
            icon: "fa fa-strikethrough"
        },
        fontSize: {
            title: "Font Size",
            icon: "fa fa-text-height",
            seed: [{
                min: 11,
                max: 52
            }]
        },
        color: {
            icon: "fa fa-tint",
            title: "Color",
            seed: [{
                cmd: "backColor",
                value: b.COLORS,
                title: "Background Color"
            }, {
                cmd: "foreColor",
                value: b.COLORS,
                title: "Text Color"
            }]
        },
        formatBlock: {
            title: "Format Block",
            icon: "fa fa-paragraph",
            seed: [{
                value: "n",
                title: "Normal"
            }, {
                value: "p",
                title: "Paragraph"
            }, {
                value: "pre",
                title: "Code"
            }, {
                value: "blockquote",
                title: "Quote"
            }, {
                value: "h1",
                title: "Heading 1"
            }, {
                value: "h2",
                title: "Heading 2"
            }, {
                value: "h3",
                title: "Heading 3"
            }, {
                value: "h4",
                title: "Heading 4"
            }, {
                value: "h5",
                title: "Heading 5"
            }, {
                value: "h6",
                title: "Heading 6"
            }]
        },
        blockStyle: {
            title: "Block Style",
            icon: "fa fa-magic"
        },
        fontFamily: {
            title: "Font Family",
            icon: "fa fa-font"
        },
        align: {
            title: "Alignment",
            icon: "fa fa-align-center",
            seed: [{
                cmd: "justifyLeft",
                title: "Align Left",
                icon: "fa fa-align-left"
            }, {
                cmd: "justifyCenter",
                title: "Align Center",
                icon: "fa fa-align-center"
            }, {
                cmd: "justifyRight",
                title: "Align Right",
                icon: "fa fa-align-right"
            }, {
                cmd: "justifyFull",
                title: "Justify",
                icon: "fa fa-align-justify"
            }]
        },
        insertOrderedList: {
            title: "Numbered List",
            icon: "fa fa-list-ol"
        },
        insertUnorderedList: {
            title: "Bulleted List",
            icon: "fa fa-list-ul"
        },
        outdent: {
            title: "Indent Less",
            icon: "fa fa-dedent",
            activeless: !0,
            shortcut: "(Ctrl + <)"
        },
        indent: {
            title: "Indent More",
            icon: "fa fa-indent",
            activeless: !0,
            shortcut: "(Ctrl + >)"
        },
        selectAll: {
            title: "Select All",
            icon: "fa fa-file-text",
            shortcut: "(Ctrl + A)"
        },
        createLink: {
            title: "Insert Link",
            icon: "fa fa-link",
            shortcut: "(Ctrl + K)"
        },
        insertImage: {
            title: "Insert Image",
            icon: "fa fa-picture-o",
            activeless: !0,
            shortcut: "(Ctrl + P)"
        },
        undo: {
            title: "Undo",
            icon: "fa fa-undo",
            activeless: !0,
            shortcut: "(Ctrl+Z)"
        },
        redo: {
            title: "Redo",
            icon: "fa fa-repeat",
            activeless: !0,
            shortcut: "(Shift+Ctrl+Z)"
        },
        html: {
            title: "Show HTML",
            icon: "fa fa-code"
        },
        save: {
            title: "Save",
            icon: "fa fa-floppy-o"
        },
        insertVideo: {
            title: "Insert Video",
            icon: "fa fa-video-camera"
        },
        insertHorizontalRule: {
            title: "Insert Horizontal Line",
            icon: "fa fa-minus"
        }
    }, b.LANGS = [], b.DEFAULTS = {
        allowedImageTypes: ["jpeg", "jpg", "png", "gif"],
        alwaysBlank: !1,
        alwaysVisible: !1,
        autosave: !1,
        autosaveInterval: 1e4,
        blockTags: ["n", "p", "blockquote", "pre", "h1", "h2", "h3", "h4", "h5", "h6"],
        defaultBlockStyle: {
            "f-italic": "Italic",
            "f-typewriter": "Typewriter",
            "f-spaced": "Spaced",
            "f-uppercase": "Uppercase"
        },
        blockStyles: {},
        borderColor: "#252528",
        buttons: ["bold", "italic", "underline", "strikeThrough", "fontSize", "fontFamily", "color", "sep", "formatBlock", "blockStyle", "align", "insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep", "createLink", "insertImage", "insertVideo", "undo", "redo", "insertHorizontalRule", "html"],
        crossDomain: !0,
        customButtons: {},
        customDropdowns: {},
        customText: !1,
        defaultImageWidth: 300,
        direction: "ltr",
        editorClass: "",
        enableScript: !1,
        fontList: ["Arial, Helvetica", "Impact, Charcoal", "Tahoma, Geneva", "Verdana, Geneva", "Times New Roman, Times"],
        height: "auto",
        icons: {},
        imageButtons: ["floatImageLeft", "floatImageNone", "floatImageRight", "linkImage", "replaceImage", "removeImage"],
        imageErrorCallback: !1,
        imageDeleteURL: null,
        imageDeleteParams: {},
        imageMargin: 10,
        imageMove: !0,
        imageUploadParams: {},
        imageUploadParam: "file",
        imageUploadURL: "http://i.froala.com/upload",
        imagesLoadURL: "http://i.froala.com/images",
        imagesLoadParams: {},
        imageUpload: !0,
        imageUploadToS3: !1,
        inverseSkin: !1,
        inlineMode: !0,
        language: "en_us",
        maxImageSize: 10485760,
        mediaManager: !0,
        minHeight: "auto",
        noFollow: !0,
        paragraphy: !0,
        placeholder: "Type something",
        plainPaste: !1,
        preloaderSrc: "",
        saveURL: null,
        saveParams: {},
        shortcuts: !0,
        spellcheck: !1,
        textNearImage: !0,
        toolbarFixed: !0,
        typingTimer: 200,
        width: "auto",
        zIndex: 1e3
    }, b.hexToRGB = function(a) {
        var b = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        a = a.replace(b, function(a, b, c, d) {
            return b + b + c + c + d + d
        });
        var c = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(a);
        return c ? {
            r: parseInt(c[1], 16),
            g: parseInt(c[2], 16),
            b: parseInt(c[3], 16)
        } : null
    }, b.hexToRGBString = function(a) {
        var b = this.hexToRGB(a);
        return "rgb(" + b.r + ", " + b.g + ", " + b.b + ")"
    }, b.getIEversion = function() {
        var a, b, c = -1;
        return "Microsoft Internet Explorer" == navigator.appName ? (a = navigator.userAgent, b = new RegExp("MSIE ([0-9]{1,}[\\.0-9]{0,})"), null !== b.exec(a) && (c = parseFloat(RegExp.$1))) : "Netscape" == navigator.appName && (a = navigator.userAgent, b = new RegExp("Trident/.*rv:([0-9]{1,}[\\.0-9]{0,})"), null !== b.exec(a) && (c = parseFloat(RegExp.$1))), c
    }, b.browser = function() {
        var a = {};
        if (b.getIEversion() > 0) a.msie = !0;
        else {
            var c = navigator.userAgent.toLowerCase(),
                d = /(chrome)[ \/]([\w.]+)/.exec(c) || /(webkit)[ \/]([\w.]+)/.exec(c) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(c) || /(msie) ([\w.]+)/.exec(c) || c.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(c) || [],
                e = {
                    browser: d[1] || "",
                    version: d[2] || "0"
                };
            d[1] && (a[e.browser] = !0), parseInt(e.version, 10) < 9 && a.msie && (a.oldMsie = !0), a.chrome ? a.webkit = !0 : a.webkit && (a.safari = !0)
        }
        return a
    }, b.prototype.text = function() {
        var a = "";
        return window.getSelection ? a = window.getSelection() : document.getSelection ? a = document.getSelection() : document.selection && (a = document.selection.createRange().text), a.toString()
    }, b.prototype.selectionInEditor = function() {
        var b = this.getSelectionParent(),
            c = !1;
        return b == this.$element.get(0) && (c = !0), c === !1 && a(b).parents().each(a.proxy(function(a, b) {
            b == this.$element.get(0) && (c = !0)
        }, this)), c
    }, b.prototype.getSelection = function() {
        var a = "";
        return a = window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection.createRange()
    }, b.prototype.getRange = function() {
        if (window.getSelection) {
            var a = window.getSelection();
            if (a.getRangeAt && a.rangeCount) return a.getRangeAt(0)
        }
        return document.createRange()
    }, b.prototype.clearSelection = function() {
        if (window.getSelection) {
            var a = window.getSelection();
            a.removeAllRanges()
        } else document.selection.createRange && (document.selection.createRange(), document.selection.empty())
    }, b.prototype.getSelectionElement = function() {
        var b = this.getSelection();
        if (b.rangeCount) {
            var c = b.getRangeAt(0).startContainer;
            return 1 != c.nodeType && (c = c.parentNode), a(c).children().length > 0 && a(a(c).children()[0]).text() == this.text() && (c = a(c).children()[0]), c
        }
        return this.$element.get(0)
    }, b.prototype.getSelectionParent = function() {
        var b, c = null;
        return window.getSelection ? (b = window.getSelection(), b.rangeCount && (c = b.getRangeAt(0).commonAncestorContainer, 1 != c.nodeType && (c = c.parentNode))) : (b = document.selection) && "Control" != b.type && (c = b.createRange().parentElement()), null != c && (a.inArray(this.$element.get(0), a(c).parents()) >= 0 || c == this.$element.get(0)) ? c : null
    }, b.prototype.nodeInRange = function(a, b) {
        var c;
        if (a.intersectsNode) return a.intersectsNode(b);
        c = b.ownerDocument.createRange();
        try {
            c.selectNode(b)
        } catch (d) {
            c.selectNodeContents(b)
        }
        return -1 == a.compareBoundaryPoints(Range.END_TO_START, c) && 1 == a.compareBoundaryPoints(Range.START_TO_END, c)
    }, b.prototype.getElementFromNode = function(c) {
        for (1 != c.nodeType && (c = c.parentNode); null !== c && b.VALID_NODES.indexOf(c.tagName) < 0;) c = c.parentNode;
        return null != c && "LI" == c.tagName && a(c).find(b.VALID_NODES.join()).length > 0 ? null : a.makeArray(a(c).parents()).indexOf(this.$element.get(0)) >= 0 ? c : null
    }, b.prototype.nextNode = function(a) {
        if (a.hasChildNodes()) return a.firstChild;
        for (; a && !a.nextSibling;) a = a.parentNode;
        return a ? a.nextSibling : null
    }, b.prototype.getRangeSelectedNodes = function(a) {
        var b = a.startContainer,
            c = a.endContainer;
        if (b == c) return [b];
        for (var d = []; b && b != c;) d.push(b = this.nextNode(b));
        for (b = a.startContainer; b && b != a.commonAncestorContainer;) d.unshift(b), b = b.parentNode;
        return d
    }, b.prototype.getSelectedNodes = function() {
        if (window.getSelection) {
            var a = window.getSelection();
            if (!a.isCollapsed) return this.getRangeSelectedNodes(a.getRangeAt(0));
            if (this.selectionInEditor()) {
                var b = a.getRangeAt(0).startContainer;
                return 3 == b.nodeType ? [b.parentNode] : [b]
            }
        }
        return []
    }, b.prototype.getSelectionElements = function() {
        var b = this.getSelectedNodes(),
            c = [];
        return a.each(b, a.proxy(function(a, b) {
            if (null !== b) {
                var d = this.getElementFromNode(b);
                c.indexOf(d) < 0 && d != this.$element.get(0) && null !== d && c.push(d)
            }
        }, this)), 0 === c.length && c.push(this.$element.get(0)), c
    }, b.prototype.getSelectionLink = function() {
        var a, b = null;
        return window.getSelection ? (a = window.getSelection(), b = 1 !== a.anchorNode.nodeType ? a.anchorNode.parentNode.parentNode.href : a.anchorNode.parentNode.href) : (a = document.selection) && "Control" != a.type && (b = a.createRange().parentElement().href), void 0 === b ? null : b
    }, b.prototype.saveSelection = function() {
        var a, b, c, d = this.getSelection();
        if (d.getRangeAt && d.rangeCount) {
            for (c = [], a = 0, b = d.rangeCount; b > a; a += 1) c.push(d.getRangeAt(a));
            this.savedRanges = c
        } else this.savedRanges = null
    }, b.prototype.restoreSelection = function() {
        var a, b, c = this.getSelection();
        if (this.savedRanges)
            for (c.removeAllRanges(), a = 0, b = this.savedRanges.length; b > a; a += 1) c.addRange(this.savedRanges[a])
    }, b.prototype.saveSelectionByMarkers = function() {
        var a = this.getRange();
        this.placeMarker(a, !0), this.placeMarker(a, !1)
    }, b.prototype.restoreSelectionByMarkers = function() {
        var a = this.$element.find("#marker-true"),
            b = this.$element.find("#marker-false");
        return a.length && b.length ? (this.$element.removeAttr("contenteditable"), this.$element.focus(), this.setSelection(a[0], 0, b[0], 0), this.removeMarkers(), this.isImage || this.isHTML || this.$element.attr("contenteditable", !0), void this.$element.focus()) : !1
    }, b.prototype.setSelection = function(a, b, c, d) {
        try {
            null === c && (c = a), null === d && (d = b);
            var e = this.getSelection();
            if (!e) return;
            var f = this.getRange();
            f.setStart(a, b), f.setEnd(c, d), e.removeAllRanges(), e.addRange(f)
        } catch (g) {}
    }, b.prototype.placeMarker = function(b, c) {
        try {
            var d = b.cloneRange();
            d.collapse(c), d.insertNode(a('<span id="marker-' + c + '">', document)[0]), d.detach()
        } catch (e) {}
    }, b.prototype.removeMarkers = function() {
        this.$element.find("#marker-true, #marker-false").removeAttr("id")
    }, b.prototype.getBoundingRect = function() {
        var b;
        if (this.browser.mozilla) {
            b = {}, this.saveSelectionByMarkers();
            var c = this.$element.find("#marker-true"),
                d = this.$element.find("#marker-false");
            b.left = c.offset().left - a(window).scrollLeft(), b.top = c.offset().top - a(window).scrollTop(), b.width = Math.abs(d.offset().left - c.offset().left), b.height = d.offset().top - c.offset().top + d.get(0).getBoundingClientRect().height, b.right = 1, b.bottom = 1, this.restoreSelectionByMarkers()
        } else b = this.getRange().getBoundingClientRect();
        return b
    }, b.prototype.repositionEditor = function(b) {
        var c, d, e;
        (this.options.inlineMode || b) && (c = this.getBoundingRect(), c.left > 0 && c.top > 0 && c.right > 0 && c.bottom > 0 ? (d = c.left + c.width / 2 + a(window).scrollLeft(), e = c.top + c.height + a(window).scrollTop(), this.isTouch() && (d = c.left + c.width / 2, e = c.top + c.height), this.showByCoordinates(d, e)) : this.options.alwaysVisible ? this.hide() : (document.execCommand("selectAll"), c = this.getBoundingRect(), d = c.left + a(window).scrollLeft(), e = c.top + c.height + a(window).scrollTop(), this.isTouch() && (d = c.left, e = c.top + c.height), this.showByCoordinates(d, e - 20), this.getRange().collapse(!1)))
    }, b.prototype.destroy = function() {
        this.sync(), this.$editor.remove(), this.$popup_editor && this.$popup_editor.remove(), this.$overlay && this.$overlay.remove(), this.$image_modal && (this.hideMediaManager(), this.$image_modal.remove()), this.$element.replaceWith(this.getHTML()), this.$box.removeClass("froala-box"), this.$box.find(".html-switch").remove(), this.$box.removeData("fa.editable"), clearTimeout(this.typingTimer), clearTimeout(this.ajaxInterval), this.$element.off("mousedown mouseup click keydown keyup focus keypress touchstart touchend touch drop"), this.$element.off("mousedown mouseup click keydown keyup focus keypress touchstart touchend touch drop", "**"), a(window).off("mouseup." + this._id), a(window).off("keydown." + this._id), a(window).off("keyup." + this._id), a(window).off("hide." + this._id), a(document).off("selectionchange." + this._id), void 0 !== this.$upload_frame && this.$upload_frame.remove(), this.$textarea && (this.$box.remove(), this.$textarea.removeData("fa.editable"), this.$textarea.show())
    }, b.prototype.callback = function(b, c, d) {
        void 0 === d && (d = !0);
        var e = b + "Callback",
            f = !0;
        return this.options[e] && a.isFunction(this.options[e]) && (f = c ? this.options[e].apply(this, c) : this.options[e].call(this)), d === !0 && (this.sync(), this.$element.focus()), void 0 === f ? !0 : f
    }, b.prototype.html5Compliant = function(b) {
        b.find("b").each(function(b, c) {
            a(c).replaceWith("<strong>" + a(c).html() + "</strong>")
        }), b.find("i:not(.fa)").each(function(b, c) {
            a(c).replaceWith("<em>" + a(c).html() + "</em>")
        })
    }, b.prototype.syncClean = function(b, c) {
        var d = "span:empty";
        c && (d = "span:empty:not(#marker-true):not(#marker-false)");
        for (var e = !1, f = function(b, c) {
                0 === c.attributes.length && (a(c).remove(), e = !1)
            }; b.find(d).length && e === !1;) e = !0, b.find(d).each(f);
        b.find("a").addClass("f-link")
    }, b.prototype.addImageWrapper = function() {
        this.isImage || this.$element.find("img").each(function(b, c) {
            0 === a(c).parents(".f-img-wrap").length && (a(c).parents("a").length > 0 ? a(a(c).parents("a")[0]).wrap('<span class="f-img-wrap"></span>') : a(c).wrap('<span class="f-img-wrap"></span>'))
        })
    }, b.prototype.sync = function() {
        this.restoreSelectionByMarkers(), this.syncClean(this.$element), this.disableImageResize(), this.$element.trigger("placeholderCheck"), this.trackHTML !== this.getHTML() && (this.callback("contentChanged", [], !1), this.refreshImageList(), this.trackHTML = this.getHTML()), this.$textarea && this.$textarea.val(this.getHTML())
    }, b.prototype.emptyElement = function(b) {
        if ("IMG" == b.tagName || a(b).find("img").length > 0) return !1;
        for (var c = a(b).text(), d = 0; d < c.length; d++)
            if ("\n" !== c[d] && "\r" !== c[d] && "	" !== c[d]) return !1;
        return !0
    }, b.prototype.init = function(a) {
        this.initElement(a), this.initElementStyle(), this.initUndoRedo(), this.enableTyping(), this.initShortcuts(), this.initEditor(), this.initDrag(), this.initOptions(), this.initEditorSelection(), this.initAjaxSaver(), this.initImageResizer(), this.initImagePopup(), this.initLink(), this.setLanguage(), this.setCustomText(), this.registerPaste(), this.$element.blur()
    }, b.prototype.initLink = function() {
        var b = this;
        this.$element.on("click touchend", "a", function(c) {
            c.stopPropagation(), c.preventDefault(), b.link = !0, b.clearSelection(), b.removeMarkers(), a(this).before('<span id="marker-true"></span>'), a(this).after('<span id="marker-false"></span>'), b.restoreSelectionByMarkers(), b.exec("createLink"), b.$link_wrapper.find('input[type="text"]').val(a(this).attr("href")), b.$link_wrapper.find('input[type="checkbox"]').prop("checked", "_blank" == a(this).attr("target")), WYSIWYGModernizr.mq("(max-device-width: 320px) and (-webkit-min-device-pixel-ratio: 2)") ? b.showByCoordinates(a(this).offset().left + a(this).width() / 2, a(this).offset().top + a(this).height()) : b.repositionEditor(!0), b.$popup_editor.show()
        })
    }, b.prototype.imageHandle = function() {
        var b = this,
            c = a("<span>").addClass("f-img-handle").on({
                movestart: function(c) {
                    b.hide(), b.$element.addClass("f-non-selectable").removeAttr("contenteditable"), b.isResizing = !0, a(this).attr("data-start-x", c.startX), a(this).attr("data-start-y", c.startY)
                },
                move: function(c) {
                    var d = a(this),
                        e = c.pageX - parseInt(d.attr("data-start-x"), 10);
                    d.attr("data-start-x", c.pageX), d.attr("data-start-y", c.pageY);
                    var f = d.prevAll("img");
                    d.hasClass("f-h-ne") || d.hasClass("f-h-se") ? f.attr("width", f.width() + e) : f.attr("width", f.width() - e), b.callback("imageResize", [], !1)
                },
                moveend: function() {
                    b.isResizing = !1, a(this).removeAttr("data-start-x"), a(this).removeAttr("data-start-y"), b.$element.removeClass("f-non-selectable"), b.isImage || b.$element.attr("contenteditable", !0), b.$element.removeAttr("data-resize"), b.callback("imageResizeEnd")
                }
            });
        return c
    }, b.prototype.disableImageResize = function() {
        if (this.browser.mozilla) try {
            document.execCommand("enableObjectResizing", !1, !1), document.execCommand("enableInlineTableEditing", !1, !1)
        } catch (a) {}
    }, b.prototype.initImageResizer = function() {
        this.disableImageResize();
        var b = this;
        this.$element.on("mousedown", "img", function() {
            b.imageHTML = b.getHTML(), !b.options.imageMove || b.browser.msie ? b.$element.attr("contenteditable", !1) : (a(this).parents(".f-img-wrap").removeAttr("contenteditable"), a(this).parent().hasClass("f-img-editor") && (b.closeImageMode(), b.hide()))
        }), document.addEventListener("drop", a.proxy(function() {
            setTimeout(a.proxy(function() {
                this.sync(), this.clearSelection()
            }, this), 10)
        }, this)), this.$element.find("img").each(function(a, b) {
            b.oncontrolselect = function() {
                return !1
            }
        }), this.$element.on("mouseup", "img", function() {
            b.options.imageMove || b.isImage || b.isHTML || b.$element.attr("contenteditable", !0), a(this).parents(".f-img-wrap").attr("contenteditable", "false")
        }), this.$element.on("click touchend", "img", function(c) {
            c.preventDefault(), c.stopPropagation(), b.$element.blur(), b.$image_editor.find("button").removeClass("active");
            var d = a(this).css("float");
            b.$image_editor.find('button[data-cmd="floatImage' + d.charAt(0).toUpperCase() + d.slice(1) + '"]').addClass("active"), b.$image_editor.find('.f-image-alt input[type="text"]').val(a(this).attr("alt") || a(this).attr("title")), b.showImageEditor(), a(this).parent().hasClass("f-img-editor") && "SPAN" == a(this).parent().get(0).tagName || (a(this).wrap('<span class="f-img-editor" style="float: ' + a(this).css("float") + "; margin-left:" + a(this).css("margin-left") + " ; margin-right:" + a(this).css("margin-right") + "; margin-bottom: " + a(this).css("margin-bottom") + "; margin-top: " + a(this).css("margin-bottom") + ';"></span>'), a(this).css("margin-left", "auto"), a(this).css("margin-right", "auto"), a(this).css("margin-bottom", "auto"), a(this).css("margin-top", "auto"), 0 !== a(this).parents(".f-img-wrap").length || b.isImage || (a(this).parent().wrap('<span class="f-img-wrap"></span>'), a(this).parents(".f-img-wrap").attr("contenteditable", !1)));
            var e = b.imageHandle();
            a(this).parent().find(".f-img-handle").remove(), a(this).parent().append(e.clone(!0).addClass("f-h-ne")), a(this).parent().append(e.clone(!0).addClass("f-h-se")), a(this).parent().append(e.clone(!0).addClass("f-h-sw")), a(this).parent().append(e.clone(!0).addClass("f-h-nw")), b.clearSelection(), b.showByCoordinates(a(this).offset().left + a(this).width() / 2, a(this).offset().top + a(this).height()), b.imageMode = !0
        }), this.$element.find("img").each(function(a, b) {
            b.oncontrolselect = function() {
                return !1
            }
        })
    }, b.prototype.initImagePopup = function() {
        this.$image_editor = a("<div>").addClass("bttn-wrapper f-image-editor");
        for (var c in this.options.imageButtons) {
            var d = this.options.imageButtons[c];
            if (void 0 !== b.image_commands[d]) {
                var e = b.image_commands[d],
                    f = a("<button>").addClass("fr-bttn").attr("data-cmd", d).attr("title", e.title);
                void 0 !== this.options.icons[d] ? this.prepareIcon(f, this.options.icons[d]) : this.prepareIcon(f, e.icon), this.$image_editor.append(f)
            }
        }
        var g = a('<div class="f-image-alt">').append('<label><span data-text="true">Title</span>: </label>').append(a('<input type="text">').on("mouseup touchend keydown", function(a) {
            a.stopPropagation()
        })).append(a('<button class="f-ok" data-text="true">').attr("data-cmd", "setImageAlt").attr("title", "OK").html("OK"));
        this.$image_editor.append("<hr/>").append(g);
        var h = this;
        this.$image_editor.find("button").click(function(b) {
            b.stopPropagation(), h[a(this).attr("data-cmd")](h.$element.find("span.f-img-editor"))
        }), this.$popup_editor.append(this.$image_editor)
    }, b.prototype.floatImageLeft = function(a) {
        a.css("margin-left", "auto"), a.css("margin-right", this.options.imageMargin), a.css("margin-bottom", this.options.imageMargin), a.css("margin-top", this.options.imageMargin), a.css("float", "left"), a.find("img").css("float", "left"), this.isImage && this.$element.css("float", "left"), this.saveUndoStep(), this.callback("floatImageLeft"), a.find("img").click()
    }, b.prototype.floatImageNone = function(a) {
        a.css("margin-left", "auto"), a.css("margin-right", "auto"), a.css("margin-bottom", this.options.imageMargin), a.css("margin-top", this.options.imageMargin), a.css("float", "none"), a.find("img").css("float", "none"), this.isImage || (a.parent().get(0) == this.$element.get(0) ? a.wrap('<div style="text-align: center;"></div>') : a.parents(".f-img-wrap:first").css("text-align", "center")), this.isImage && this.$element.css("float", "none"), this.saveUndoStep(), this.callback("floatImageNone"), a.find("img").click()
    }, b.prototype.floatImageRight = function(a) {
        a.css("margin-right", "auto"), a.css("margin-left", this.options.imageMargin), a.css("margin-bottom", this.options.imageMargin), a.css("margin-top", this.options.imageMargin), a.css("float", "right"), a.find("img").css("float", "right"), this.isImage && this.$element.css("float", "right"), this.saveUndoStep(), this.callback("floatImageRight"), a.find("img").click()
    }, b.prototype.linkImage = function(a) {
        this.showInsertLink(), this.imageMode = !0, "A" == a.parent().get(0).tagName ? (this.$link_wrapper.find('input[type="text"]').val(a.parent().attr("href")), "_blank" == a.parent().attr("target") ? this.$link_wrapper.find('input[type="checkbox"]').prop("checked", !0) : this.$link_wrapper.find('input[type="checkbox"]').prop("checked", !1)) : (this.$link_wrapper.find('input[type="text"]').val("http://"), this.$link_wrapper.find('input[type="checkbox"]').prop("checked", this.options.alwaysBlank))
    }, b.prototype.replaceImage = function(a) {
        this.showInsertImage(), this.imageMode = !0, this.$image_wrapper.find('input[type="text"]').val(a.find("img").attr("src"))
    }, b.prototype.removeImage = function(c) {
        var d = c.find("img").get(0),
            e = "Are you sure? Image will be deleted.";
        if (b.LANGS[this.options.language] && (e = b.LANGS[this.options.language].translation[e]), confirm(e)) {
            if (this.callback("beforeRemoveImage", [a(d).attr("src")], !1) === !0) {
                var f = a(d).attr("src");
                c.parent(".f-img-wrap").remove(), this.refreshImageList(!0), this.hide(), this.saveUndoStep(), this.callback("afterRemoveImage", [f]), this.focus()
            }
        } else c.find("img").click()
    }, b.prototype.setImageAlt = function(a) {
        a.find("img").attr("alt", this.$image_editor.find('.f-image-alt input[type="text"]').val()), a.find("img").attr("title", this.$image_editor.find('.f-image-alt input[type="text"]').val()), this.saveUndoStep(), this.hide(), this.closeImageMode(), this.callback("setImageAlt")
    }, b.prototype.getSelectionTextInfo = function(a) {
        var b, c, d = !1,
            e = !1;
        if (window.getSelection) {
            var f = window.getSelection();
            f.rangeCount && (b = f.getRangeAt(0), c = b.cloneRange(), c.selectNodeContents(a), c.setEnd(b.startContainer, b.startOffset), d = "" === c.toString(), c.selectNodeContents(a), c.setStart(b.endContainer, b.endOffset), e = "" === c.toString())
        } else document.selection && "Control" != document.selection.type && (b = document.selection.createRange(), c = b.duplicate(), c.moveToElementText(a), c.setEndPoint("EndToStart", b), d = "" === c.text, c.moveToElementText(a), c.setEndPoint("StartToEnd", b), e = "" === c.text);
        return {
            atStart: d,
            atEnd: e
        }
    }, b.prototype.endsWith = function(a, b) {
        return -1 !== a.indexOf(b, a.length - b.length)
    }, b.prototype.initElement = function(b) {
        "TEXTAREA" == b.tagName ? (this.$textarea = a(b), void 0 !== this.$textarea.attr("placeholder") && "Type something" == this.options.placeholder && (this.options.placeholder = this.$textarea.attr("placeholder")), this.$element = a("<div>").html(this.$textarea.val()), this.$textarea.before(this.$element).hide(), this.$textarea.on("submit", a.proxy(function() {
            this.sync()
        }, this))) : "IMG" == b.tagName ? ("A" == a(b).parent().tagName && (b = a(b).parent()), this.isImage = !0, this.imageList = [], this.options.paragraphy = !1, this.options.imageMargin = "auto", a(b).wrap("<div>"), this.$element = a(b).parent(), this.$element.css("display", "inline-block"), this.$element.css("max-width", "100%"), this.$element.css("margin", "auto")) : ("DIV" != b.tagName && this.options.buttons.indexOf("formatBlock") >= 0 && this.disabledList.push("formatBlock"), this.$element = a(b)), this.isImage || (this.$box = this.$element, this.$element = a("<div>"), this.setHTML(this.$box.html(), !1), this.$box.empty(), this.$box.html(this.$element).addClass("froala-box"), this.$element.on("keydown", a.proxy(function(b) {
            var c = b.which,
                d = ["PRE", "BLOCKQUOTE"],
                e = this.getSelectionElements()[0];
            if (13 == c && "" === this.text() && d.indexOf(e.tagName) >= 0)
                if (this.getSelectionTextInfo(e).atEnd && !b.shiftKey) {
                    b.preventDefault();
                    var f = a("<p><br></p>");
                    a(e).after(f), this.setSelection(f.get(0), 0, null, 0)
                } else(this.browser.webkit || this.browser.msie) && (b.preventDefault(), this.insertHTML(this.endsWith(a(e).html(), "<br>") || !this.getSelectionTextInfo(e).atEnd ? "<br>" : "<br><br>"))
        }, this)), this.$element.on("keyup", a.proxy(function(b) {
            this.refreshButtons();
            var c = b.which;
            if (13 == c && "" === this.text() && this.browser.safari) {
                var d = a(this.getSelectionElement());
                if (d.parent("li").length) {
                    this.saveSelectionByMarkers(), d.before('<span id="li-before"></span>'), d.after('<span id="li-after"></span>');
                    var e = this.$element.html();
                    e = e.replace(/<span id=\"li-before\"><\/span>/g, "</li><li>"), e = e.replace(/<span id=\"li-after\"><\/span>/g, "</li>"), this.$element.html(e), this.restoreSelectionByMarkers()
                }
            }
            13 == c && this.webkitParagraphy()
        }, this))), this.trackHTML = this.getHTML(), this.sync(), this.$element.on("drop", function() {
            setTimeout(function() {
                a("html").click()
            }, 1)
        })
    }, b.prototype.webkitParagraphy = function() {
        this.$element.find("*").each(a.proxy(function(b, c) {
            if (this.emptyElement(c) && "DIV" == c.tagName && this.options.paragraphy === !0) {
                var d = a("<p><br/></p>");
                a(c).replaceWith(d), this.setSelection(d.get(0), 0, null, 0)
            }
        }, this))
    }, b.prototype.refreshImageList = function(b) {
        this.addImageWrapper();
        var c = [];
        if (this.$element.find("img").each(function(b, d) {
                c.push(a(d).attr("src"))
            }), void 0 === b)
            for (var d = 0; d < this.imageList.length; d++) c.indexOf(this.imageList[d]) < 0 && this.callback("afterRemoveImage", [this.imageList[d]], !1);
        this.imageList = c
    }, b.prototype.trim = function(a) {
        return String(a).replace(/^\s+|\s+$/g, "")
    }, b.prototype.unwrapText = function() {
        this.options.paragraphy || this.$element.find("div").each(function(b, c) {
            void 0 === a(c).attr("style") && a(c).replaceWith(a(c).html() + "<br/>")
        })
    }, b.prototype.wrapText = function() {
        if (this.isImage) return !1;
        this.webkitParagraphy();
        var c = [],
            d = ["SPAN", "A", "B", "I", "EM", "U", "S", "STRONG", "STRIKE", "FONT"],
            e = this,
            f = function() {
                var b;
                if (b = a(e.options.paragraphy === !0 ? "<p>" : "<div>"), 1 == c.length && ("marker-false" === a(c[0]).attr("id") || "marker-true" === a(c[0]).attr("id") || "" === a(c[0]).text())) return void(c = []);
                for (var d in c) b.append(a(c[d]).clone()), d == c.length - 1 ? a(c[d]).replaceWith(b) : a(c[d]).remove();
                c = []
            };
        this.$element.contents().filter(function() {
            this.nodeType == Node.TEXT_NODE && a(this).text().trim().length > 0 || d.indexOf(this.tagName) >= 0 ? c.push(this) : this.nodeType == Node.TEXT_NODE && 0 === a(this).text().trim().length ? a(this).remove() : f()
        }), f(), this.options.paragraphy && this.$element.find("img").each(a.proxy(function(c, d) {
            for (var e = a(d).parent(); e.get(0) != this.$element.get(0);) {
                if (b.VALID_NODES.indexOf(e.get(0).tagName) >= 0) return !0;
                e = e.parent()
            }
            a(d).parents(".f-img-wrap").length > 0 ? a(d).parents(".f-img-wrap").wrap("<p></p>") : a(d).wrap("<p></p>")
        }, this)), this.$element.find("> p, > div").each(function(b, c) {
            0 === a(c).text().trim().length && 0 === a(c).find("img").length && 0 === a(c).find("br").length && a(c).append("<br/>")
        }), this.$element.find("div:empty, > br").remove()
    }, b.prototype.setHTML = function(a, b) {
        void 0 === b && (b = !0), this.options.enableScript || (a = this.stripScript(a)), this.$element.html(this.clean(a, !0, !1)), this.imageList = [], this.refreshImageList(), this.options.paragraphy && this.wrapText(), b === !0 && this.sync()
    }, b.prototype.registerPaste = function() {
        var b = this;
        this.$element.get(0).onpaste = function() {
            if (!b.isHTML) {
                if (b.callback("beforePaste", [], !1) !== !0) return !1;
                b.pasting = !0, b.saveSelectionByMarkers();
                var c = a(window).scrollTop(),
                    d = a('<div contenteditable="true"></div>').appendTo("body").focus();
                window.setTimeout(function() {
                    var e = d.html();
                    d.remove(), a(window).scrollTop(c), b.restoreSelectionByMarkers(), b.insertHTML(b.options.plainPaste ? a("<div>").html(e).text() : b.clean(e, !1, !0)), b.sync(), b.$element.trigger("placeholderCheck"), b.pasting = !1, b.callback("afterPaste")
                }, 1)
            }
        }
    }, b.prototype._extractContent = function(a) {
        for (var b, c = document.createDocumentFragment(); b = a.firstChild;) c.appendChild(b);
        return c
    }, b.prototype.clean = function(b, c, d) {
        var e = ["title", "href", "alt", "src", "style", "width", "height", "target", "rel", "name", "value", "type"],
            f = ["PRE", "BLOCKQUOTE"];
        c === !0 && (e.push("id"), e.push("class"));
        var g = this,
            h = a("<div>").html(b);
        return h.find("*").each(function(b, c) {
            f.indexOf(c.tagName) >= 0 && a(c).html(a(c).text()), a.each(c.attributes, function() {
                void 0 !== this && e.indexOf(this.name) < 0 && 0 !== this.name.indexOf("data-") && a(c).removeAttr(this.name)
            }), d === !0 && a(c).removeAttr("style"), "A" == c.tagName && a(c).attr("href", g.sanitizeURL(a(c).attr("href")))
        }), this.cleanNewLine(h.html())
    }, b.prototype.cleanNewLine = function(a) {
        var b = new RegExp("\\n", "g");
        return a.replace(b, "")
    }, b.prototype.stripScript = function(a) {
        if (this.options.enableScript) return a;
        var b = document.createElement("div");
        b.innerHTML = a;
        for (var c = b.getElementsByTagName("script"), d = c.length; d--;) c[d].parentNode.removeChild(c[d]);
        return b.innerHTML
    }, b.prototype.initElementStyle = function() {
        this.isImage || this.$element.attr("contenteditable", !0), this.$element.addClass("froala-element").addClass(this.options.editorClass), this.$element.css("outline", 0), this.browser.msie || this.$element.addClass("not-msie")
    }, b.prototype.initUndoRedo = function() {
        (this.isEnabled("undo") || this.isEnabled("redo")) && (this.undoStack = [], this.undoIndex = 0, this.saveUndoStep()), this.disableBrowserUndo()
    }, b.prototype.enableTyping = function() {
        this.typingTimer = null, this.$element.on("keydown", a.proxy(function() {
            clearTimeout(this.typingTimer), this.ajaxSave = !1, this.oldHTML = this.$element.html(), this.typingTimer = setTimeout(a.proxy(function() {
                this.$element.html() != this.oldHTML && ((this.isEnabled("undo") || this.isEnabled("redo")) && this.$element.html() != this.undoStack[this.undoIndex - 1] && this.saveUndoStep(), this.sync())
            }, this), Math.max(this.options.typingTimer, 200))
        }, this))
    }, b.prototype.getHTML = function(b) {
        if (this.isHTML) return this.$html_area.val();
        var c = this.$element.clone();
        this.html5Compliant(c), this.syncClean(c, b), c.find(".f-img-editor > img").each(function(b, c) {
            a(c).css("margin-left", a(c).parent().css("margin-left")), a(c).css("margin-right", a(c).parent().css("margin-right")), a(c).css("margin-bottom", a(c).parent().css("margin-bottom")), a(c).css("margin-top", a(c).parent().css("margin-top")), a(c).siblings("span.f-img-handle").remove().end().unwrap()
        }), c.find(".f-img-wrap").removeAttr("contenteditable"), this.isImage && c.find(".f-img-wrap").each(function(b, c) {
            a(c).replaceWith(a(c).html())
        }), void 0 === b && c.find("span#marker-true, span#marker-false").remove(), c.find("a:empty").remove();
        var d = c.html();
        return d.replace(/\u200B/g, "")
    }, b.prototype.getText = function() {
        return this.$element.text()
    }, b.prototype.initAjaxSaver = function() {
        this.ajaxHTML = this.getHTML(), this.ajaxSave = !0, this.ajaxInterval = setInterval(a.proxy(function() {
            this.ajaxHTML != this.getHTML() && this.ajaxSave && (this.options.autosave && this.save(), this.ajaxHTML = this.getHTML()), this.ajaxSave = !0
        }, this), Math.max(this.options.autosaveInterval, 100))
    }, b.prototype.disableBrowserUndo = function() {
        a("body").keydown(function(a) {
            var b = a.which,
                c = a.ctrlKey || a.metaKey;
            if (!this.isHTML && c) {
                if (75 == b) return a.preventDefault(), !1;
                if (90 == b && a.shiftKey) return a.preventDefault(), !1;
                if (90 == b) return a.preventDefault(), !1
            }
        })
    }, b.prototype.saveUndoStep = function() {
        if (this.isEnabled("undo") || this.isEnabled("redo")) {
            for (; this.undoStack.length > this.undoIndex;) this.undoStack.pop();
            this.selectionInEditor() && this.$element.is(":focus") && this.saveSelectionByMarkers(), this.undoStack.push(this.getHTML(!0)), this.undoIndex++, this.selectionInEditor() && this.$element.is(":focus") && this.restoreSelectionByMarkers(), this.refreshUndoRedo()
        }
    }, b.prototype.initShortcuts = function() {
        this.options.shortcuts && this.$element.on("keydown", a.proxy(function(a) {
            var b = a.which,
                c = a.ctrlKey || a.metaKey;
            if (!this.isHTML && c) {
                if (70 == b) return this.show(null), !1;
                if (66 == b) return this.execDefaultShortcut("bold");
                if (73 == b) return this.execDefaultShortcut("italic");
                if (85 == b) return this.execDefaultShortcut("underline");
                if (75 == b) return this.execDefaultShortcut("createLink");
                if (80 == b) return this.repositionEditor(), this.execDefaultShortcut("insertImage");
                if (65 == b) return this.execDefaultShortcut("selectAll");
                if (221 == b) return this.execDefaultShortcut("indent");
                if (219 == b) return this.execDefaultShortcut("outdent");
                if (72 == b) return this.execDefaultShortcut("html");
                if (48 == b) return this.execDefaultShortcut("formatBlock", "n");
                if (49 == b) return this.execDefaultShortcut("formatBlock", "h1");
                if (50 == b) return this.execDefaultShortcut("formatBlock", "h2");
                if (51 == b) return this.execDefaultShortcut("formatBlock", "h3");
                if (52 == b) return this.execDefaultShortcut("formatBlock", "h4");
                if (53 == b) return this.execDefaultShortcut("formatBlock", "h5");
                if (54 == b) return this.execDefaultShortcut("formatBlock", "h6");
                if (222 == b) return this.execDefaultShortcut("formatBlock", "blockquote");
                if (220 == b) return this.execDefaultShortcut("formatBlock", "pre");
                if (46 == b || 8 == b) return this.execDefaultShortcut("strikeThrough");
                if (90 == b && a.shiftKey) return this.redo(), a.stopPropagation(), !1;
                if (90 == b) return this.undo(), a.stopPropagation(), !1
            }
            9 != b || a.shiftKey ? 9 == b && a.shiftKey && a.preventDefault() : (a.preventDefault(), this.insertHTML("&nbsp;&nbsp;&nbsp;&nbsp;", !1))
        }, this))
    }, b.prototype.textEmpty = function(b) {
        return ("" === a(b).text() || b === this.$element.get(0)) && 0 === a(b).find("br").length
    }, b.prototype.focus = function() {
        if (!this.isHTML && (this.$element.focus(), "" === this.text())) {
            var a, c, d = this.getSelectionElements();
            for (a in d)
                if (c = d[a], !this.textEmpty(c)) return void this.setSelection(c, 0, null, 0);
            d = this.$element.find(b.VALID_NODES.join(","));
            for (a in d)
                if (c = d[a], !this.textEmpty(c)) return void this.setSelection(c, 0, null, 0);
            this.setSelection(this.$element.get(0), 0, null, 0)
        }
    }, b.prototype.insertHTML = function(a, b) {
        this.isHTML || (this.$element.focus(), this.selectionInEditor() || this.setSelection(this.$element.get(0), 0, null, 0));
        var c, d;
        if (window.getSelection) {
            if (c = window.getSelection(), c.getRangeAt && c.rangeCount) {
                d = c.getRangeAt(0), d.deleteContents();
                var e = document.createElement("div");
                e.innerHTML = a;
                for (var f, g, h = document.createDocumentFragment(); f = e.firstChild;) g = h.appendChild(f);
                var i = h.firstChild;
                d.insertNode(h), g && (d = d.cloneRange(), d.setStartAfter(g), b ? d.setStartBefore(i) : d.collapse(!0), c.removeAllRanges(), c.addRange(d))
            }
        } else if ((c = document.selection) && "Control" != c.type) {
            var j = c.createRange();
            j.collapse(!0), c.createRange().pasteHTML(a), b && (d = c.createRange(), d.setEndPoint("StartToStart", j), d.select())
        }
    }, b.prototype.execDefaultShortcut = function(a, b) {
        return this.isEnabled(a) ? (this.exec(a, b), !1) : !0
    }, b.prototype.initEditor = function() {
        this.$editor = a("<div>"), this.$editor.addClass("froala-editor").hide(), a("body").append(this.$editor), this.options.inlineMode ? this.initInlineEditor() : this.initBasicEditor()
    }, b.prototype.toolbarTop = function() {
        a(window).on("scroll resize", a.proxy(function() {
            this.options.toolbarFixed || this.options.inlineMode || (a(window).scrollTop() > this.$box.offset().top && a(window).scrollTop() < this.$box.offset().top + this.$box.height() ? (this.$editor.addClass("f-scroll"), this.$box.css("padding-top", this.$editor.height()), this.$editor.css("top", a(window).scrollTop() - this.$box.offset().top)) : a(window).scrollTop() < this.$box.offset().top && (this.$editor.removeClass("f-scroll"), this.$box.css("padding-top", ""), this.$editor.css("top", "")))
        }, this))
    }, b.prototype.initBasicEditor = function() {
        this.$element.addClass("f-basic"), this.$popup_editor = this.$editor.clone(), this.$popup_editor.appendTo(a("body")), this.$editor.addClass("f-basic").show(), this.$editor.insertBefore(this.$element), this.toolbarTop()
    }, b.prototype.initInlineEditor = function() {
        this.$popup_editor = this.$editor
    }, b.prototype.initDrag = function() {
        this.drag_support = {
            filereader: "undefined" != typeof FileReader,
            formdata: !!window.FormData,
            progress: "upload" in new XMLHttpRequest
        }
    }, b.prototype.initOptions = function() {
        this.setDimensions(), this.setDirection(), this.setBorderColor(), this.setPlaceholder(), this.setPlaceholderEvents(), this.setSpellcheck(), this.setImageUploadURL(), this.setButtons(), this.setInverseSkin(), this.setTextNearImage(), this.setZIndex()
    }, b.prototype.setImageUploadURL = function(a) {
        a && (this.options.imageUploadURL = a), this.options.imageUploadToS3 && (this.options.imageUploadURL = "https://" + this.options.imageUploadToS3.bucket + "." + this.options.imageUploadToS3.region + ".amazonaws.com/")
    }, b.prototype.closeImageMode = function() {
        this.$element.find("span.f-img-editor > img").each(function(b, c) {
            a(c).css("margin-left", a(c).parent().css("margin-left")), a(c).css("margin-right", a(c).parent().css("margin-right")), a(c).css("margin-bottom", a(c).parent().css("margin-bottom")), a(c).css("margin-top", a(c).parent().css("margin-top")), a(c).siblings("span.f-img-handle").remove().end().unwrap()
        }), this.$element.find("span.f-img-editor").length && (this.$element.find("span.f-img-editor").remove(), this.$element.parents("span.f-img-editor").remove()), this.$element.removeClass("f-non-selectable"), this.isImage || this.isHTML || this.$element.attr("contenteditable", !0), this.$image_editor.hide()
    }, b.prototype.isTouch = function() {
        return WYSIWYGModernizr.touch && void 0 !== window.Touch
    }, b.prototype.initEditorSelection = function() {
        a(window).on("hide." + this._id, a.proxy(function() {
            this.hide(!1)
        }, this)), this.$element.on("mousedown touchstart", a.proxy(function() {
            this.$element.attr("data-resize") || (this.closeImageMode(), this.hide())
        }, this)), this.$element.contextmenu(a.proxy(function(a) {
            return a.preventDefault(), this.options.inlineMode && this.$element.focus(), !1
        }, this)), this.$element.on("mouseup touchend", a.proxy(function(a) {
            var b = this.text();
            "" === b && !this.options.alwaysVisible && (3 != a.which && 2 != a.button || !this.options.inlineMode || this.isImage) || this.isTouch() ? this.options.inlineMode || this.refreshButtons() : (a.stopPropagation(), this.show(a)), this.imageMode = !1
        }, this)), this.$element.on("mousedown touchstart", "img, a", a.proxy(function(a) {
            this.isResizing || this.$element.attr("data-resize") || a.stopPropagation()
        }, this)), this.$element.on("mousedown touchstart", ".f-img-handle", a.proxy(function() {
            this.$element.attr("data-resize", !0)
        }, this)), this.$element.on("mouseup touchend", ".f-img-handle", a.proxy(function() {
            this.$element.removeAttr("data-resize")
        }, this)), this.$editor.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation(), this.options.inlineMode === !1 && this.hide()
        }, this)), this.$popup_editor.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        }, this)), this.$link_wrapper && this.$link_wrapper.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        })), this.$image_wrapper && this.$image_wrapper.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        })), this.$video_wrapper && this.$video_wrapper.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        })), this.$overlay && this.$overlay.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        })), this.$image_modal && this.$image_modal.on("mouseup touchend", a.proxy(function(a) {
            a.stopPropagation()
        })), a(window).on("mouseup." + this._id, a.proxy(function() {
            this.selectionInEditor() && "" !== this.text() && !this.isTouch() ? this.show(null) : (this.hide(), this.closeImageMode())
        }, this)), a(window).on("mouseup touchend", a.proxy(function() {
            a(window).trigger("window." + this._id)
        }, this)), a(document).on("selectionchange." + this._id, a.proxy(function(a) {
            if (this.options.inlineMode && this.selectionInEditor() && this.link !== !0 && this.isTouch()) {
                var b = this.text();
                "" !== b ? (WYSIWYGModernizr.mq("(max-device-width: 320px) and (-webkit-min-device-pixel-ratio: 2)") ? this.hide() : this.show(null), a.stopPropagation()) : this.hide()
            }
        }, this)), a(document).on("selectionchange", function(b) {
            a(document).trigger("selectionchange." + this._id, [b])
        }), a(window).bind("keydown." + this._id, a.proxy(function(a) {
            var b = a.which;
            if (this.imageMode) {
                if (13 == b) return this.$element.find(".f-img-editor").parents(".f-img-wrap").before("<br/>"), this.sync(), this.$element.find(".f-img-editor img").click(), !1;
                if (46 == b || 8 == b) return this.removeImage(this.$element.find(".f-img-editor")), !1
            }
            a.ctrlKey || (this.hide(), this.closeImageMode())
        }, this)), a(window).bind("keydown", function(b) {
            a(window).trigger("keydown." + this._id, [b])
        }), a(window).bind("keyup." + this._id, a.proxy(function() {
            this.selectionInEditor() && "" !== this.text() && this.repositionEditor()
        }, this)), a(window).bind("keyup", function(b) {
            a(window).trigger("keyup." + this._id, [b])
        })
    }, b.prototype.setTextNearImage = function(a) {
        void 0 !== a && (this.options.textNearImage = a), this.options.textNearImage === !0 ? this.$element.removeClass("f-tni") : this.$element.addClass("f-tni")
    }, b.prototype.setPlaceholder = function(a) {
        a && (this.options.placeholder = a), this.$textarea && this.$textarea.attr("placeholder", this.options.placeholder), this.$element.attr("data-placeholder", this.options.placeholder)
    }, b.prototype.isEmpty = function() {
        var a = this.$element.text().replace(/(\r\n|\n|\r|\t)/gm, "");
        return "" === a && 0 === this.$element.find("img, iframe, input").length && 0 === this.$element.find("p > br, div > br").length && 0 === this.$element.find("li, h1, h2, h3, h4, h5, h6, blockquote, pre").length
    }, b.prototype.fakeEmpty = function(a) {
        void 0 === a && (a = this.$element);
        var b = a.text().replace(/(\r\n|\n|\r|\t)/gm, "");
        return "" === b && 1 == a.find("p, div").length && 1 == a.find("p > br, div > br").length && 0 === a.find("img, iframe").length
    }, b.prototype.setPlaceholderEvents = function() {
        this.$element.on("keyup keydown focus placeholderCheck", a.proxy(function() {
            if (this.pasting) return !1;
            if (!this.isEmpty() || this.fakeEmpty() || this.isHTML) !this.$element.find("p").length && this.options.paragraphy ? (this.wrapText(), this.$element.find("p, div").length ? this.setSelection(this.$element.find("p, div")[0], this.$element.find("p, div").text().length, null, this.$element.find("p, div").text().length) : this.$element.removeClass("f-placeholder")) : this.fakeEmpty() === !1 || this.$element.find(b.VALID_NODES.join(",")).length > 1 ? this.$element.removeClass("f-placeholder") : this.$element.addClass("f-placeholder");
            else {
                var c, d = this.selectionInEditor() || this.$element.is(":focus");
                this.options.paragraphy ? (c = a("<p><br/></p>"), this.$element.html(c), d && this.setSelection(c.get(0), 0, null, 0), this.$element.addClass("f-placeholder")) : this.$element.addClass("f-placeholder")
            }
        }, this)), this.$element.trigger("placeholderCheck")
    }, b.prototype.setDimensions = function(a, b, c) {
        a && (this.options.height = a), b && (this.options.width = b), c && (this.options.minHeight = c), "auto" != this.options.height && this.$element.css("height", this.options.height), "auto" != this.options.minHeight && this.$element.css("minHeight", this.options.minHeight), "auto" != this.options.width && this.$box.css("width", this.options.width)
    }, b.prototype.setDirection = function(a) {
        a && (this.options.direction = a), "ltr" != this.options.direction && "rtl" != this.options.direction && (this.options.direction = "ltr"), "rtl" == this.options.direction ? (this.$element.addClass("f-rtl"), this.$editor.addClass("f-rtl"), this.$popup_editor.addClass("f-rtl")) : (this.$element.removeClass("f-rtl"), this.$editor.removeClass("f-rtl"), this.$popup_editor.removeClass("f-rtl"))
    }, b.prototype.setZIndex = function(a) {
        a && (this.options.zIndex = a), this.$popup_editor.css("z-index", this.options.zIndex), this.$overlay && this.$overlay.css("z-index", this.options.zIndex + 1), this.$image_modal && this.$image_modal.css("z-index", this.options.zIndex + 2)
    }, b.prototype.setBorderColor = function(a) {
        a && (this.options.borderColor = a);
        var c = b.hexToRGB(this.options.borderColor);
        null !== c && (this.$editor.css("border-color", this.options.borderColor), this.$editor.attr("data-border-color", this.options.borderColor), this.$image_modal && this.$image_modal.find(".f-modal-wrapper").css("border-color", this.options.borderColor), this.options.inlineMode || this.$element.css("border-color", this.options.borderColor))
    }, b.prototype.setSpellcheck = function(a) {
        void 0 !== a && (this.options.spellcheck = a), this.$element.attr("spellcheck", this.options.spellcheck)
    }, b.prototype.setInverseSkin = function(a) {
        void 0 !== a && (this.options.inverseSkin = a), this.options.inverseSkin ? (this.$editor.addClass("f-inverse"), this.$popup_editor.addClass("f-inverse")) : (this.$editor.removeClass("f-inverse"), this.$popup_editor.removeClass("f-inverse"))
    }, b.prototype.customizeText = function(b) {
        if (b) {
            var c = this.$editor.find("[title]").add(this.$popup_editor.find("[title]"));
            this.$image_modal && (c = c.add(this.$image_modal.find("[title]"))), c.each(a.proxy(function(c, d) {
                for (var e in b) a(d).attr("title").toLowerCase() == e.toLowerCase() && a(d).attr("title", b[e])
            }, this)), c = this.$editor.find('[data-text="true"]').add(this.$popup_editor.find('[data-text="true"]')), this.$image_modal && (c = c.add(this.$image_modal.find('[data-text="true"]'))), c.each(a.proxy(function(c, d) {
                for (var e in b) a(d).text().toLowerCase() == e.toLowerCase() && a(d).text(b[e])
            }, this))
        }
    }, b.prototype.setLanguage = function(b) {
        void 0 !== b && (this.options.language = b), a.Editable.LANGS[this.options.language] && (this.customizeText(a.Editable.LANGS[this.options.language].translation), a.Editable.LANGS[this.options.language].direction && this.setDirection(a.Editable.LANGS[this.options.language].direction), a.Editable.LANGS[this.options.language].translation[this.options.placeholder] && this.setPlaceholder(a.Editable.LANGS[this.options.language].translation[this.options.placeholder]))
    }, b.prototype.setCustomText = function(a) {
        a && (this.options.customText = a), this.options.customText && this.customizeText(this.options.customText)
    }, b.prototype.execHTML = function() {
        this.html()
    }, b.prototype.initHTMLArea = function() {
        this.$html_area = a('<textarea wrap="hard">').keydown(function(b) {
            var c = b.keyCode || b.which;
            if (9 == c) {
                b.preventDefault();
                var d = a(this).get(0).selectionStart,
                    e = a(this).get(0).selectionEnd;
                a(this).val(a(this).val().substring(0, d) + "	" + a(this).val().substring(e)), a(this).get(0).selectionStart = a(this).get(0).selectionEnd = d + 1
            }
        })
    }, b.prototype.setButtons = function(c) {
        c && (this.options.buttons = c), this.$bttn_wrapper = a("<div>").addClass("bttn-wrapper"), this.$editor.append(this.$bttn_wrapper);
        for (var d in this.options.buttons) {
            var e, f;
            "sep" == this.options.buttons[d] && this.$bttn_wrapper.append(this.options.inlineMode ? '<div class="f-clear"></div><hr/>' : '<span class="f-sep"></span>');
            var g = b.commands[this.options.buttons[d]];
            if (void 0 !== g) switch (g.cmd = this.options.buttons[d], g.cmd) {
                case "color":
                    e = this.buildDropdownColor(g), f = this.buildDropdownButton(g, "fr-color-picker").append(e), this.$bttn_wrapper.append(f);
                    break;
                case "align":
                    e = this.buildDropdownAlign(g), f = this.buildDropdownButton(g, "fr-selector").append(e), this.$bttn_wrapper.append(f);
                    break;
                case "fontSize":
                    e = this.buildDropdownFontsize(g), f = this.buildDropdownButton(g).append(e), this.$bttn_wrapper.append(f);
                    break;
                case "formatBlock":
                    e = this.buildDropdownFormatBlock(g), f = this.buildDropdownButton(g).append(e), this.$bttn_wrapper.append(f);
                    break;
                case "blockStyle":
                    e = this.buildDropdownBlockStyle(g), f = this.buildDropdownButton(g).append(e), this.$bttn_wrapper.append(f);
                    break;
                case "fontFamily":
                    e = this.buildDropdownFontFamily(), f = this.buildDropdownButton(g, "fr-family").append(e), this.$bttn_wrapper.append(f);
                    break;
                case "createLink":
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f);
                    break;
                case "insertImage":
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f);
                    break;
                case "insertVideo":
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f), this.buildInsertVideo();
                    break;
                case "undo":
                case "redo":
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f), f.prop("disabled", !0);
                    break;
                case "html":
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f), this.options.inlineMode && this.$box.append(f.clone(!0).addClass("html-switch").attr("title", "Hide HTML").click(a.proxy(this.execHTML, this))), this.initHTMLArea();
                    break;
                default:
                    f = this.buildDefaultButton(g), this.$bttn_wrapper.append(f)
            } else {
                if (g = this.options.customButtons[this.options.buttons[d]], void 0 === g) {
                    if (g = this.options.customDropdowns[this.options.buttons[d]], void 0 === g) continue;
                    f = this.buildCustomDropdown(g), this.$bttn_wrapper.append(f);
                    continue
                }
                f = this.buildCustomButton(g), this.$bttn_wrapper.append(f)
            }
        }
        this.buildCreateLink(), this.buildInsertImage(), this.options.mediaManager && this.buildMediaManager(), this.bindButtonEvents()
    }, b.prototype.buildDefaultButton = function(b) {
        var c = a("<button>").addClass("fr-bttn").attr("title", b.title).attr("data-cmd", b.cmd).attr("data-activeless", b.activeless);
        return void 0 !== this.options.icons[b.cmd] ? this.prepareIcon(c, this.options.icons[b.cmd]) : this.addButtonIcon(c, b), c
    }, b.prototype.prepareIcon = function(a, b) {
        "font" == b.type ? this.addButtonIcon(a, {
            icon: b.value
        }) : "img" == b.type ? this.addButtonIcon(a, {
            icon_img: b.value,
            title: a.attr("title")
        }) : "txt" == b.type && this.addButtonIcon(a, {
            icon_txt: b.value
        })
    }, b.prototype.addButtonIcon = function(b, c) {
        b.append(c.icon ? a("<i>").addClass(c.icon) : c.icon_alt ? a("<i>").addClass("for-text").html(c.icon_alt) : c.icon_img ? a('<img src="' + c.icon_img + '">').attr("alt", c.title) : c.icon_txt ? a("<i>").html(c.icon_txt) : c.title)
    }, b.prototype.buildCustomButton = function(b) {
        var c = a("<button>").addClass("fr-bttn").attr("title", b.title);
        return this.prepareIcon(c, b.icon), c.on("click touchend", a.proxy(function(a) {
            a.stopPropagation(), a.preventDefault(), b.callback(this)
        }, this)), c
    }, b.prototype.buildCustomDropdown = function(b) {
        var c = a("<div>").addClass("fr-bttn fr-dropdown"),
            d = a("<button>").addClass("fr-trigger").attr("title", b.title);
        this.prepareIcon(d, b.icon), c.append(d);
        var e = a("<ul>").addClass("fr-dropdown-menu");
        for (var f in b.options) {
            var g = a("<li>").append(a('<a href="#">').append(f).on("click touch", b.options[f]));
            e.append(g)
        }
        return c.append(e)
    }, b.prototype.buildDropdownButton = function(b, c) {
        c = c || "";
        var d = a("<div>").addClass("fr-bttn fr-dropdown").addClass(c),
            e = a("<button>").addClass("fr-trigger").attr("title", b.title);
        return this.addButtonIcon(e, b), d.append(e), d
    }, b.prototype.buildDropdownColor = function(b) {
        var c = a("<div>").addClass("fr-dropdown-menu");
        for (var d in b.seed) {
            var e = b.seed[d],
                f = a("<div>").append(a('<p data-text="true">').html(e.title));
            for (var g in e.value) {
                var h = e.value[g];
                f.append(a("<button>").addClass("fr-color-bttn").attr("data-cmd", e.cmd).attr("data-val", h).attr("data-activeless", b.activeless).css("background-color", h).html("&nbsp;")), g % 8 == 7 && g > 0 && (f.append("<hr/>"), (7 == g || 15 == g) && f.append(a("<div>").addClass("separator")))
            }
            c.append(f)
        }
        return c
    }, b.prototype.buildDropdownAlign = function(b) {
        var c = a("<ul>").addClass("fr-dropdown-menu");
        for (var d in b.seed) {
            var e = b.seed[d],
                f = a("<li>").append(a("<button>").addClass("fr-bttn").attr("data-cmd", e.cmd).attr("title", e.title).attr("data-activeless", b.activeless).append(a("<i>").addClass(e.icon)));
            c.append(f)
        }
        return c
    }, b.prototype.buildDropdownFontsize = function(b) {
        var c = a("<ul>").addClass("fr-dropdown-menu f-font-sizes");
        for (var d in b.seed)
            for (var e = b.seed[d], f = e.min; f <= e.max; f++) {
                var g = a("<li>").attr("data-cmd", b.cmd).attr("data-val", f + "px").attr("data-activeless", b.activeless).append(a('<a href="#">').append(a("<span>").text(f + "px")));
                c.append(g)
            }
        return c
    }, b.prototype.buildDropdownFormatBlock = function(b) {
        var c = a("<ul>").addClass("fr-dropdown-menu");
        for (var d in b.seed) {
            var e = b.seed[d];
            if (-1 != a.inArray(e.value, this.options.blockTags)) {
                var f = a("<li>").append(a("<li>").attr("data-cmd", b.cmd).attr("data-val", e.value).attr("data-activeless", b.activeless).append(a('<a href="#" data-text="true">').addClass("format_" + e.value).attr("title", e.title).text(e.title)));
                c.append(f)
            }
        }
        return c
    }, b.prototype.buildDropdownBlockStyle = function() {
        var b = a("<ul>").addClass("fr-dropdown-menu fr-block-style");
        return b.append(a('<li data-cmd="blockStyle" data-val="*">').append(a('<a href="#" data-text="true">').text("Default"))), b
    }, b.prototype.buildDropdownFontFamily = function() {
        var b = a("<ul>").addClass("fr-dropdown-menu");
        for (var c in this.options.fontList) {
            var d = this.options.fontList[c],
                e = a("<li>").attr("data-cmd", "fontFamily").attr("data-val", d).append(a('<a href="#" data-text="true">').attr("title", d).css("font-family", d).text(d));
            b.append(e)
        }
        return b
    }, b.prototype.buildMediaManager = function() {
        this.$image_modal = a("<div>").addClass("froala-modal").appendTo("body"), this.$overlay = a("<div>").addClass("froala-overlay").appendTo("body");
        var c = a("<div>").addClass("f-modal-wrapper").appendTo(this.$image_modal);
        a("<h4>").append('<span data-text="true">Manage images</span>').append(a('<i class="fa fa-times" title="Cancel">').click(a.proxy(function() {
            this.hideMediaManager()
        }, this))).appendTo(c), this.$preloader = a('<img src="' + this.options.preloaderSrc + '" alt="Loading..."/>').addClass("f-preloader").appendTo(c), this.$preloader.hide(), this.$media_images = a("<div>").addClass("f-image-list").appendTo(c), WYSIWYGModernizr.touch && this.$media_images.addClass("f-touch"), this.$media_images.on("click touch", "img", a.proxy(function(b) {
            b.stopPropagation();
            var c = b.currentTarget;
            this.writeImage(a(c).attr("src")), this.hideMediaManager()
        }, this)), this.$media_images.on("click touchend", ".f-delete-img", a.proxy(function(c) {
            c.stopPropagation();
            var d = a(c.currentTarget).prev(),
                e = "Are you sure? Image will be deleted.";
            b.LANGS[this.options.language] && (e = b.LANGS[this.options.language].translation[e]), confirm(e) && this.callback("beforeDeleteImage", [a(d).attr("src")], !1) !== !1 && (a(d).remove(), this.deleteImage(a(d).attr("src")))
        }, this)), this.hideMediaManager()
    }, b.prototype.deleteImage = function(b) {
        this.options.imageDeleteURL ? a.post(this.options.imageDeleteURL, a.extend({
            src: b
        }, this.options.imageDeleteParams), a.proxy(function(a) {
            this.callback("imageDeleteSuccess", [a], !1)
        }, this)) : this.callback("imageDeleteError", ["Missing imageDeleteURL option."], !1)
    }, b.prototype.loadImage = function(c) {
        var d = new Image,
            e = a("<div>").addClass("f-empty");
        d.onload = a.proxy(function() {
            var a = "Delete";
            b.LANGS[this.options.language] && (a = b.LANGS[this.options.language].translation[a]), e.append('<img src="' + c + '"/><a class="f-delete-img"><span data-text="true">' + a + "</span></a>"), e.removeClass("f-empty"), this.$media_images.hide(), this.$media_images.show(), this.callback("imageLoaded", [c], !1)
        }, this), d.onerror = a.proxy(function() {
            e.remove(), this.throwImageError(1)
        }, this), d.src = c, this.$media_images.append(e)
    }, b.prototype.processLoadedImages = function(a) {
        try {
            var b = a;
            this.$media_images.empty();
            for (var c = 0; c < b.length; c++) this.loadImage(b[c])
        } catch (d) {
            this.throwImageError(4)
        }
    }, b.prototype.loadImages = function() {
        this.$preloader.show(), this.$media_images.empty(), this.options.imagesLoadURL ? a.get(this.options.imagesLoadURL, this.options.imagesLoadParams, a.proxy(function(a) {
            this.callback("imagesLoaded", [a], !1), this.processLoadedImages(a), this.$preloader.hide()
        }, this), "json").fail(a.proxy(function() {
            this.callback("imagesLoadError", ["Load request failed."], !1), this.$preloader.hide()
        }, this)) : (this.callback("imagesLoadError", ["Missing imagesLoadURL option."], !1), this.$preloader.hide())
    }, b.prototype.showMediaManager = function() {
        this.$image_modal.show(), this.$overlay.show(), this.loadImages(), a("body").css("overflow", "hidden")
    }, b.prototype.hideMediaManager = function() {
        this.$image_modal.hide(), this.$overlay.hide(), a("body").css("overflow", "")
    }, b.prototype.buildInsertImage = function() {
        this.$image_wrapper = a("<div>").addClass("image-wrapper"), this.$popup_editor.append(this.$image_wrapper);
        var c = this;
        this.$progress_bar = a('<p class="f-progress">').append("<span></span>");
        var d = null;
        if (this.options.imageUpload) {
            if (d = a('<div class="f-upload">').append('<strong data-text="true">Drop Image</strong><br>(<span data-text="true">or click</span>)').append(a('<form method="post" action="' + this.options.imageUploadURL + '" encoding="multipart/form-data" enctype="multipart/form-data" target="frame-' + this._id + '">').append(a('<input type="file" accept="image/*" name="' + this.options.imageUploadParam + '" />'))), this.browser.msie && b.getIEversion() <= 9 && null !== d) {
                this.$upload_frame = a('<iframe id="frame-' + this._id + '" name="frame-' + c._id + '" src="javascript:false;" style="width:0; height:0; border:0px solid #FFF;" data-loaded="true">'), d.find("form").append(this.$upload_frame);
                var e = this.$upload_frame.bind("load", function() {
                    e.unbind("load"), e.bind("load", function() {
                        try {
                            if (c.options.imageUploadToS3) {
                                var b = d.find("form").attr("action"),
                                    e = d.find('form input[name="key"]').val(),
                                    f = b + e;
                                c.writeImage(f), c.options.imageUploadToS3.callback && c.options.imageUploadToS3.callback.call(this, f, e)
                            } else {
                                var g = a(this).contents().text();
                                c.parseImageResponse(g)
                            }
                        } catch (h) {
                            c.throwImageError(7)
                        }
                    })
                })
            }
            this.$image_wrapper.on("change", 'input[type="file"]', function() {
                if (void 0 !== this.files) c.uploadFile(this.files), setTimeout(function() {
                    c.showInsertImage()
                }, 500);
                else {
                    var b = a(this).parents("form");
                    b.find('input[type="hidden"]').remove();
                    var d;
                    for (d in c.options.imageUploadParams) b.prepend('<input type="hidden" name="' + d + '" value="' + c.options.imageUploadParams[d] + '" />');
                    if (void 0 !== c.options.imageUploadToS3) {
                        for (d in c.options.imageUploadToS3.params) b.prepend('<input type="hidden" name="' + d + '" value="' + c.options.imageUploadToS3.params[d] + '" />');
                        b.prepend('<input type="hidden" name="success_action_status" value="201" />'), b.prepend('<input type="hidden" name="X-Requested-With" value="xhr" />'), b.prepend('<input type="hidden" name="Content-Type" value="" />'), b.prepend('<input type="hidden" name="key" value="' + c.options.imageUploadToS3.keyStart + (new Date).getTime() + "-" + a(this).val().match(/[^\/\\]+$/) + '" />')
                    } else b.prepend('<input type="hidden" name="XHR_CORS_TRARGETORIGIN" value="' + window.location.href + '" />');
                    b.submit(), c.$image_list.hide(), c.$progress_bar.show(), c.$progress_bar.find("span").css("width", "100%").text("Please wait!")
                }
            }), this.buildDragUpload(d), d = a('<li class="drop-upload">').append(d)
        }
        var f = a('<input type="text" placeholder="http://example.com"/>').on("mouseup touchend keydown", a.proxy(function(a) {
                a.stopPropagation()
            }, this)),
            g = null;
        this.options.mediaManager && (g = a('<button class="f-browse">').append('<i class="fa fa-search"></i>').on("click", a.proxy(function() {
            this.showMediaManager()
        }, this)).find("i").click(a.proxy(function() {
            this.showMediaManager()
        }, this)).end()), this.$image_list = a("<ul>").append(d).append(a('<li class="url-upload">').append('<label><span data-text="true">Enter URL</span>: </label>').append(f).append(g).append(a('<button class="f-ok" data-text="true">OK</button>').click(a.proxy(function() {
            this.writeImage(f.val(), !0)
        }, this)))), this.$image_wrapper.append(a("<h4>").append('<span data-text="true">Insert image</span>').append(a('<i class="fa fa-times" title="Cancel">').click(a.proxy(function() {
            this.$bttn_wrapper.show(), this.hideImageWrapper(!0), this.restoreSelection(), this.options.inlineMode || this.imageMode ? this.imageMode && this.showImageEditor() : this.hide()
        }, this)))).append(this.$image_list).append(this.$progress_bar).click(function(a) {
            a.stopPropagation()
        }).find("*").click(function(a) {
            a.stopPropagation()
        }).end().hide()
    }, b.prototype.writeVideo = function(a) {
        this.$element.focus(), this.restoreSelection(), this.insertHTML(this.stripScript(a)), this.saveUndoStep(), this.$bttn_wrapper.show(), this.hideVideoWrapper(), this.hide(), this.callback("insertVideo", [a])
    }, b.prototype.buildInsertVideo = function() {
        this.$video_wrapper = a("<div>").addClass("video-wrapper"), this.$popup_editor.append(this.$video_wrapper);
        var b = a('<textarea placeholder="Embeded code">').on("mouseup touchend keydown", a.proxy(function(a) {
                a.stopPropagation()
            }, this)),
            c = a("<p>").append(a('<button class="f-ok" data-text="true">OK</button>').click(a.proxy(function() {
                this.writeVideo(b.val())
            }, this)));
        this.$video_wrapper.append(a("<h4>").append('<span data-text="true">Insert video</span>').append(a('<i class="fa fa-times" title="Cancel">').click(a.proxy(function() {
            this.$bttn_wrapper.show(), this.hideVideoWrapper(), this.restoreSelection(), this.options.inlineMode || this.hide()
        }, this)))).append(b).append(c).click(function(a) {
            a.stopPropagation()
        }).find("*").click(function(a) {
            a.stopPropagation()
        }).end().hide()
    }, b.prototype.buildCreateLink = function() {
        this.$link_wrapper = a("<div>").addClass("link-wrapper"), this.$popup_editor.append(this.$link_wrapper);
        var b = a('<input type="text">').attr("placeholder", "http://www.example.com").on("mouseup touchend keydown", function(a) {
                a.stopPropagation()
            }),
            c = a('<input type="checkbox" id="f-checkbox-' + this._id + '">').click(function(a) {
                a.stopPropagation()
            }),
            d = a('<button class="f-ok" type="button" data-text="true">').text("OK").on("touchend", function(a) {
                a.stopPropagation()
            }).click(a.proxy(function() {
                this.writeLink(b.val(), c.prop("checked"))
            }, this)),
            e = a('<button class="f-ok f-unlink" data-text="true" type="button">').text("UNLINK").on("click touch", a.proxy(function() {
                this.link = !0, this.writeLink("", c.prop("checked"))
            }, this));
        this.$link_wrapper.append(a("<h4>").append('<span data-text="true">Insert link</span>').append(a('<i class="fa fa-times" title="Cancel">').click(a.proxy(function() {
            this.$bttn_wrapper.show(), this.hideLinkWrapper(), this.options.inlineMode || this.imageMode ? this.imageMode && this.showImageEditor() : this.hide(), this.restoreSelection()
        }, this)))).append(b).append(a("<p>").append(c).append(' <label for="f-checkbox-' + this._id + '" data-text="true">Open in new tab</label>').append(d).append(e)).end().hide()
    }, b.prototype.buildDragUpload = function(b) {
        var c = this;
        b.on({
            dragover: function() {
                return a(this).addClass("f-hover"), !1
            },
            dragend: function() {
                return a(this).removeClass("f-hover"), !1
            },
            drop: function(b) {
                a(this).removeClass("f-hover"), b.preventDefault(), c.uploadFile(b.originalEvent.dataTransfer.files)
            }
        })
    }, b.prototype.hideImageLoader = function() {
        this.$progress_bar.hide(), this.$progress_bar.find("span").css("width", "0%").text(""), this.$image_list.show()
    }, b.prototype.writeImage = function(b, c) {
        c && (b = this.sanitizeURL(b));
        var d = new Image;
        return d.onerror = a.proxy(function() {
            this.hideImageLoader(), this.throwImageError(1)
        }, this), this.imageMode ? (d.onload = a.proxy(function() {
            this.$element.find(".f-img-editor > img").attr("src", b), this.hide(), this.hideImageLoader(), this.$image_editor.show(), this.saveUndoStep(), this.callback("replaceImage", [b])
        }, this), d.src = b, !1) : (d.onload = a.proxy(function() {
            this.$element.focus(), this.restoreSelection(), this.callback("imageLoaded", [b], !1);
            var c = '<img alt="Image title" src="' + b + '" width="' + this.options.defaultImageWidth + '" style="min-width: 16px; min-height: 16px; margin-bottom: ' + this.options.imageMargin + "px; margin-left: auto; margin-right: auto; margin-top: " + this.options.imageMargin + 'px">',
                d = this.getSelectionElements()[0];
            this.getSelectionTextInfo(d).atStart && d != this.$element.get(0) ? a(d).before("<p>" + c + "</p>") : this.insertHTML(c), this.$element.find("img").each(function(a, b) {
                b.oncontrolselect = function() {
                    return !1
                }
            }), this.hide(), this.hideImageLoader(), this.saveUndoStep(), this.callback("insertImage", [b])
        }, this), void(d.src = b))
    }, b.prototype.throwImageError = function(b) {
        var c = "Unknown image upload error.";
        1 == b ? c = "Bad link." : 2 == b ? c = "No link in upload response." : 3 == b ? c = "Error during file upload." : 4 == b ? c = "Parsing response failed." : 5 == b ? c = "Image too large." : 6 == b ? c = "Invalid image type." : 7 == b && (c = "Image can be uploaded only to same domain in IE 9."), this.options.imageErrorCallback && a.isFunction(this.options.imageErrorCallback) && this.options.imageErrorCallback({
            errorCode: b,
            errorStatus: c
        }), this.hideImageLoader()
    }, b.prototype.uploadFile = function(b) {
        if (this.callback("beforeFileUpload", [b], !1) !== !0) return !1;
        if (void 0 !== b && b.length > 0) {
            var c;
            if (this.drag_support.formdata && (c = this.drag_support.formdata ? new FormData : null), c) {
                var d;
                for (d in this.options.imageUploadParams) c.append(d, this.options.imageUploadParams[d]);
                if (void 0 !== this.options.imageUploadToS3) {
                    for (d in this.options.imageUploadToS3.params) c.append(d, this.options.imageUploadToS3.params[d]);
                    c.append("success_action_status", "201"), c.append("X-Requested-With", "xhr"), c.append("Content-Type", b[0].type), c.append("key", this.options.imageUploadToS3.keyStart + (new Date).getTime() + "-" + b[0].name)
                }
                if (c.append(this.options.imageUploadParam, b[0]), b[0].size > this.options.maxImageSize) return this.throwImageError(5), !1;
                if (this.options.allowedImageTypes.indexOf(b[0].type.replace(/image\//g, "")) < 0) return this.throwImageError(6), !1
            }
            if (c) {
                var e;
                this.options.crossDomain ? e = this.createCORSRequest("POST", this.options.imageUploadURL) : (e = new XMLHttpRequest, e.open("POST", this.options.imageUploadURL)), e.onload = a.proxy(function() {
                    this.$progress_bar.find("span").css("width", "100%").text("Please wait!");
                    try {
                        200 == e.status ? this.parseImageResponse(e.responseText) : 201 == e.status ? this.parseImageResponseXML(e.responseXML) : this.throwImageError(3)
                    } catch (a) {
                        this.throwImageError(4)
                    }
                }, this), e.onerror = a.proxy(function() {
                    this.throwImageError(3)
                }, this), e.upload.onprogress = a.proxy(function(a) {
                    if (a.lengthComputable) {
                        var b = a.loaded / a.total * 100 | 0;
                        this.$progress_bar.find("span").css("width", b + "%")
                    }
                }, this), e.send(c), this.$image_list.hide(), this.$progress_bar.show()
            }
        }
    }, b.prototype.parseImageResponse = function(b) {
        try {
            var c = a.parseJSON(b);
            c.link ? this.writeImage(c.link) : this.throwImageError(2)
        } catch (d) {
            this.throwImageError(4)
        }
    }, b.prototype.parseImageResponseXML = function(b) {
        try {
            var c = a(b).find("Location").text(),
                d = a(b).find("Key").text();
            this.options.imageUploadToS3.callback.call(this, c, d), c ? this.writeImage(c) : this.throwImageError(2)
        } catch (e) {
            this.throwImageError(4)
        }
    }, b.prototype.createCORSRequest = function(a, b) {
        var c = new XMLHttpRequest;
        return "withCredentials" in c ? c.open(a, b, !0) : "undefined" != typeof XDomainRequest ? (c = new XDomainRequest, c.open(a, b)) : c = null, c
    }, b.prototype.writeLink = function(b, c, d) {
        this.options.noFollow && (d = !0), this.options.alwaysBlank && (c = !0);
        var e = "",
            f = "";
        if (d === !0 && (e = 'rel="nofollow"'), c === !0 && (f = 'target="_blank"'), b = this.sanitizeURL(b), this.imageMode) return "" !== b ? ("A" != this.$element.find(".f-img-editor").parent().get(0).tagName ? this.$element.find(".f-img-editor").wrap('<a class="f-link" href="' + b + '" ' + f + " " + e + "></a>") : (c === !0 ? this.$element.find(".f-img-editor").parent().attr("target", "_blank") : this.$element.find(".f-img-editor").parent().removeAttr("target"), d === !0 ? this.$element.find(".f-img-editor").parent().attr("rel", "nofollow") : this.$element.find(".f-img-editor").parent().removeAttr("rel"), this.$element.find(".f-img-editor").parent().attr("href", b)), this.callback("insertImageLink", [b])) : ("A" == this.$element.find(".f-img-editor").parent().get(0).tagName && a(this.$element.find(".f-img-editor").get(0)).unwrap(), this.callback("removeImageLink")), this.saveUndoStep(), this.showImageEditor(), this.$element.find(".f-img-editor").find("img").click(), this.link = !1, !1;
        if (this.restoreSelection(), document.execCommand("unlink", !1, b), this.saveSelectionByMarkers(), this.$element.find("span.f-link").each(function(b, c) {
                a(c).replaceWith(a(c).html())
            }), this.restoreSelectionByMarkers(), "" !== b) {
            document.execCommand("createLink", !1, b);
            for (var g = this.getSelectionLinks(), h = 0; h < g.length; h++) c === !0 && a(g[h]).attr("target", "_blank"), d === !0 && a(g[h]).attr("rel", "nofollow"), a(g[h]).addClass("f-link");
            this.$element.find("a:empty").remove(), this.callback("insertLink", [b])
        } else this.$element.find("a:empty").remove(), this.callback("removeLink");
        this.saveUndoStep(), this.hideLinkWrapper(), this.$bttn_wrapper.show(), this.options.inlineMode || this.hide(), this.link = !1
    }, b.prototype.getSelectionLinks = function() {
        var a, b, c, d, e = [];
        if (window.getSelection) {
            var f = window.getSelection();
            if (f.getRangeAt && f.rangeCount) {
                d = document.createRange();
                for (var g = 0; g < f.rangeCount; ++g)
                    if (a = f.getRangeAt(g), b = a.commonAncestorContainer, 1 != b.nodeType && (b = b.parentNode), "a" == b.nodeName.toLowerCase()) e.push(b);
                    else {
                        c = b.getElementsByTagName("a");
                        for (var h = 0; h < c.length; ++h) d.selectNodeContents(c[h]), d.compareBoundaryPoints(a.END_TO_START, a) < 1 && d.compareBoundaryPoints(a.START_TO_END, a) > -1 && e.push(c[h])
                    }
                d.detach()
            }
        } else if (document.selection && "Control" != document.selection.type)
            if (a = document.selection.createRange(), b = a.parentElement(), "a" == b.nodeName.toLowerCase()) e.push(b);
            else {
                c = b.getElementsByTagName("a"), d = document.body.createTextRange();
                for (var i = 0; i < c.length; ++i) d.moveToElementText(c[i]), d.compareEndPoints("StartToEnd", a) > -1 && d.compareEndPoints("EndToStart", a) < 1 && e.push(c[i])
            }
        return e
    }, b.prototype.isEnabled = function(b) {
        return a.inArray(b, this.options.buttons) >= 0
    }, b.prototype.show = function(b) {
        if (void 0 !== b) {
            if (this.options.inlineMode)
                if (null !== b && "touchend" !== b.type) {
                    var c = b.pageX,
                        d = b.pageY;
                    c < this.$element.offset().left && (c = this.$element.offset().left), c > this.$element.offset().left + this.$element.width() && (c = this.$element.offset().left + this.$element.width()), d < this.$element.offset.top && (d = this.$element.offset().top), d > this.$element.offset().top + this.$element.height() && (d = this.$element.offset().top + this.$element.height()), 20 > c && (c = 20), 0 > d && (d = 0), c + this.$editor.width() > a(window).width() - 50 ? (this.$editor.addClass("right-side"), c = a(window).width() - (c + 30), this.$editor.css("top", d + 20), this.$editor.css("right", c), this.$editor.css("left", "auto")) : (this.$editor.removeClass("right-side"), this.$editor.css("top", d + 20), this.$editor.css("left", c - 20), this.$editor.css("right", "auto")), a(".froala-editor:not(.f-basic)").hide(), this.$editor.show()
                } else a(".froala-editor:not(.f-basic)").hide(), this.$editor.show(), this.repositionEditor();
            this.hideLinkWrapper(), this.hideVideoWrapper(), this.hideImageWrapper(), this.$bttn_wrapper.show(), this.$bttn_wrapper.find(".fr-dropdown").removeClass("active"), this.refreshButtons(), this.imageMode = !1
        }
    }, b.prototype.showByCoordinates = function(b, c) {
        b -= 20, c += 15, b + this.$popup_editor.width() > a(window).width() - 50 ? (this.$popup_editor.addClass("right-side"), b = a(window).width() - (b + 40), this.$popup_editor.css("top", c), this.$popup_editor.css("right", b), this.$popup_editor.css("left", "auto")) : (this.$popup_editor.removeClass("right-side"), this.$popup_editor.css("top", c), this.$popup_editor.css("left", b), this.$popup_editor.css("right", "auto")), this.$popup_editor.show()
    }, b.prototype.showLinkWrapper = function() {
        this.$link_wrapper && (this.$link_wrapper.show(), setTimeout(a.proxy(function() {
            this.$link_wrapper.find('input[type="text"]').focus().select()
        }, this), 0))
    }, b.prototype.hideLinkWrapper = function() {
        this.$link_wrapper && this.$link_wrapper.hide()
    }, b.prototype.showImageWrapper = function() {
        this.$image_wrapper && this.$image_wrapper.show()
    }, b.prototype.showVideoWrapper = function() {
        this.$video_wrapper && this.$video_wrapper.show()
    }, b.prototype.hideImageWrapper = function(a) {
        this.$image_wrapper && (this.$element.attr("data-resize") || a || this.closeImageMode(), this.$image_wrapper.hide())
    }, b.prototype.hideVideoWrapper = function() {
        this.$video_wrapper && this.$video_wrapper.hide()
    }, b.prototype.showInsertLink = function() {
        this.options.inlineMode && this.$bttn_wrapper.hide(), this.showLinkWrapper(), this.hideImageWrapper(!0), this.hideVideoWrapper(), this.$image_editor.hide(), this.link = !0
    }, b.prototype.showInsertImage = function() {
        this.options.inlineMode && this.$bttn_wrapper.hide(), this.hideLinkWrapper(), this.hideVideoWrapper(), this.showImageWrapper(), this.$image_editor.hide()
    }, b.prototype.showInsertVideo = function() {
        this.options.inlineMode && this.$bttn_wrapper.hide(), this.hideLinkWrapper(), this.hideImageWrapper(), this.showVideoWrapper(), this.$image_editor.hide()
    }, b.prototype.showImageEditor = function() {
        this.options.inlineMode && this.$bttn_wrapper.hide(), this.hideLinkWrapper(), this.hideImageWrapper(!0), this.hideVideoWrapper(), this.$image_editor.show(), this.options.imageMove || this.$element.attr("contenteditable", !1)
    }, b.prototype.hideOtherEditors = function() {
        for (var c = 1; c <= b.count; c++) c != this._id && a(window).trigger("hide." + c)
    }, b.prototype.hide = function(a) {
        void 0 === a && (a = !0), a ? this.hideOtherEditors() : (this.closeImageMode(), this.imageMode = !1), this.$popup_editor.hide(), this.hideLinkWrapper(), this.hideImageWrapper(), this.hideVideoWrapper(), this.$image_editor.hide(), this.link = !1
    }, b.prototype.positionPopup = function(b) {
        a(this.$editor.find('button.fr-bttn[data-cmd="' + b + '"]')).length && (this.$popup_editor.css("top", this.$editor.find('button.fr-bttn[data-cmd="' + b + '"]').offset().top + 30), this.$popup_editor.css("left", this.$editor.find('button.fr-bttn[data-cmd="' + b + '"]').offset().left), this.$popup_editor.show())
    }, b.prototype.bindButtonEvents = function() {
        this.bindDropdownEvents(), this.bindCommandEvents(this.$bttn_wrapper.find("[data-cmd]"))
    }, b.prototype.bindDropdownEvents = function() {
        var b = this;
        this.$bttn_wrapper.find(".fr-dropdown").on("click touchend", function(c) {
            return c.stopPropagation(), c.preventDefault(), b.options.inlineMode === !1 && b.hide(), a(this).attr("data-disabled") ? !1 : (a(".fr-dropdown").not(this).removeClass("active"), void a(this).toggleClass("active"))
        }), a(window).on("click touchend", a.proxy(function() {
            this.$editor.find(".fr-dropdown").removeClass("active")
        }, this)), this.$element.on("click touchend", "img, a", a.proxy(function() {
            this.$editor.find(".fr-dropdown").removeClass("active")
        }, this));
        var c = this.$bttn_wrapper.find(".fr-selector button.fr-bttn");
        c.bind("select", function() {
            a(this).parents(".fr-selector").find(" > button > i").attr("class", a(this).find("i").attr("class"))
        }).on("click touch", function() {
            a(this).parents("ul").find("button").removeClass("active"), a(this).parents(".fr-selector").removeClass("active").trigger("mouseout"), a(this).trigger("select")
        })
    }, b.prototype.bindCommandEvents = function(b) {
        b.on("touchmove", function() {
            a(this).data("dragging", !0)
        }), b.on("click touchend", a.proxy(function(b) {
            b.stopPropagation(), b.preventDefault();
            var c = b.currentTarget;
            if (a(c).data("dragging")) return a(c).removeData("dragging"), !1;
            var d = a(c).data("cmd"),
                e = a(c).data("val");
            a(c).parents(".fr-dropdown").removeClass("active"), this.exec(d, e), this.$bttn_wrapper.find(".fr-dropdown").removeClass("active")
        }, this))
    }, b.prototype._startInDefault = function(a) {
        this.$element.focus(), this.$bttn_wrapper.find('[data-cmd="' + a + '"]').toggleClass("active"), document.execCommand(a)
    }, b.prototype._startInFontExec = function(a, b, c) {
        this.$element.focus(), this.insertHTML('<span data-inserted="true" data-font="' + b + '" style="' + a + ": " + c + '"></span>', !0);
        var d = this.$element.find("[data-inserted]");
        d.removeAttr("data-inserted"), this.setSelection(d.get(0), 0, null, 0)
    }, b.prototype.exec = function(a, b) {
        if (!this.selectionInEditor() && "html" !== a && "undo" !== a && "redo" !== a && "selectAll" !== a && "save" != a && "insertImage" !== a && "insertVideo" !== a) return !1;
        if (this.selectionInEditor()) {
            if ("" === this.text()) {
                if ("bold" === a || "italic" === a || "underline" === a || "strikeThrough" == a) return this._startInDefault(a), !1;
                if ("fontSize" == a) return this._startInFontExec("font-size", a, b), !1;
                if ("fontFamily" == a) return this._startInFontExec("font-family", a, b), !1
            }
            if ("" === this.text() && "insertHorizontalRule" != a && "fontSize" !== a && "formatBlock" !== a && "blockStyle" !== a && "indent" !== a && "outdent" !== a && "justifyLeft" !== a && "justifyRight" !== a && "justifyFull" !== a && "justifyCenter" !== a && "html" !== a && "undo" !== a && "redo" !== a && "selectAll" !== a && "save" !== a && "insertImage" !== a && "insertVideo" !== a && "insertOrderedList" !== a && "insertUnorderedList" !== a) return !1
        }
        switch (a) {
            case "fontSize":
                this.fontExec("font-size", a, b);
                break;
            case "fontFamily":
                this.fontExec("font-family", a, b);
                break;
            case "backColor":
                this.backColor(b);
                break;
            case "foreColor":
                this.foreColor(b);
                break;
            case "formatBlock":
                this.formatBlock(b);
                break;
            case "blockStyle":
                this.blockStyle(b);
                break;
            case "createLink":
                this.insertLink();
                break;
            case "insertImage":
                this.insertImage();
                break;
            case "indent":
                this.indent();
                break;
            case "outdent":
                this.outdent(!0);
                break;
            case "justifyLeft":
            case "justifyRight":
            case "justifyCenter":
            case "justifyFull":
                this.align(a);
                break;
            case "insertOrderedList":
            case "insertUnorderedList":
                this.formatList(a);
                break;
            case "insertVideo":
                this.insertVideo();
                break;
            case "indent":
            case "outdent":
                this.execDefault(a, b), this.repositionEditor();
                break;
            case "undo":
                this.undo();
                break;
            case "redo":
                this.redo();
                break;
            case "html":
                this.html();
                break;
            case "save":
                this.save();
                break;
            case "selectAll":
                this.$element.focus(), this.execDefault(a, b);
                break;
            case "insertHorizontalRule":
                this.execDefault(a, b), this.hide();
                break;
            default:
                this.execDefault(a, b)
        }
        "undo" != a && "redo" != a && "selectAll" != a && "createLink" != a && "insertImage" != a && "html" != a && "insertVideo" != a && this.saveUndoStep(), "createLink" != a && "insertImage" != a && this.refreshButtons()
    }, b.prototype.removeFormat = function() {
        document.execCommand("removeFormat", !1, !1), document.execCommand("unlink", !1, !1)
    }, b.prototype.undo = function() {
        if (this.undoIndex > 1) {
            var a = this.getHTML(),
                b = this.undoStack[--this.undoIndex - 1];
            this.$element.html(b), this.$element.focus(), this.restoreSelectionByMarkers(), this.hide(), this.callback("undo", [this.$element.html(), a])
        }
        this.refreshUndoRedo()
    }, b.prototype.redo = function() {
        if (this.undoIndex < this.undoStack.length) {
            var a = this.$element.html(),
                b = this.undoStack[this.undoIndex++];
            this.$element.html(b), this.$element.focus(), this.restoreSelectionByMarkers(), this.hide(), this.callback("redo", [this.$element.html(), a])
        }
        this.refreshUndoRedo()
    }, b.prototype.save = function() {
        return this.callback("beforeSave", [], !1) !== !0 ? !1 : void(this.options.saveURL ? a.post(this.options.saveURL, a.extend({
            body: this.getHTML()
        }, this.options.saveParams), a.proxy(function(a) {
            this.callback("afterSave", [a])
        }, this)).fail(a.proxy(function() {
            this.callback("saveError", ["Save request failed on the server."])
        }, this)) : this.callback("saveError", ["Missing save URL."]))
    }, b.prototype.sanitizeURL = function(a) {
        return this.options.enableScript ? a : /^https?:\/\//.test(a) ? String(a).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;") : encodeURIComponent(a).replace("%23", "#").replace("%2F", "/")
    }, b.prototype.html = function() {
        var a;
        this.isHTML ? (this.isHTML = !1, a = this.options.enableScript ? this.$html_area.val() : this.stripScript(this.$html_area.val()), a = this.clean(a, !0, !1), this.$element.html(a).attr("contenteditable", !0), this.$box.removeClass("f-html"), this.$editor.find('.fr-bttn:not([data-cmd="html"])').prop("disabled", !1), this.$editor.find("div.fr-bttn").removeAttr("data-disabled"), this.$editor.find('.fr-bttn[data-cmd="html"]').removeClass("active"), this.saveUndoStep(), this.options.pragraphy && this.wrapText(), this.refreshButtons(), this.callback("htmlHide", [a]), this.focus()) : (a = this.options.inlineMode ? "\n\n" + this.getHTML() : html_beautify(this.getHTML()), this.$html_area.val(a).trigger("resize"), this.options.inlineMode && this.$box.find(".html-switch").css("top", this.$box.css("padding-top")), this.$html_area.css("height", this.$element.height() + 20), this.$element.html(this.$html_area).removeAttr("contenteditable"), this.$box.addClass("f-html"), this.$editor.find('button.fr-bttn:not([data-cmd="html"])').prop("disabled", !0), this.$editor.find("div.fr-bttn").attr("data-disabled", !0), this.$editor.find('.fr-bttn[data-cmd="html"]').addClass("active"), this.options.inlineMode && this.hide(), this.isHTML = !0, this.callback("htmlShow", [a]))
    }, b.prototype.beautifyFont = function(b) {
        for (var c = !0, d = a.proxy(function() {
                this.$element.find('span[data-font="' + b + '"] + span[data-font="' + b + '"]').each(function(d, e) {
                    a(e).css(b) == a(e).prev().css(b) && (a(e).prepend(a(e).prev().html()), a(e).prev().remove(), c = !0)
                }), this.$element.find('span[data-font="' + b + '"] + span#marker-true + span[data-font="' + b + '"], span[data-font="' + b + '"] + span#marker-false + span[data-font="' + b + '"]').each(function(d, e) {
                    a(e).css(b) == a(e).prev().prev().css(b) && (a(e).prepend(a(e).prev().clone()), a(e).prepend(a(e).prev().prev().html()), a(e).prev().prev().remove(), a(e).prev().remove(), c = !0)
                })
            }, this); c;) c = !1, d()
    }, b.prototype.fontExec = function(b, c, d) {
        document.execCommand("fontSize", !1, 1), this.saveSelectionByMarkers();
        var e = [];
        this.$element.find("font").each(function(c, f) {
            var g = a("<span>").attr("data-font", b).css(b, d).html(a(f).html());
            0 === a(f).parents("font").length && e.push(g), a(f).replaceWith(g)
        });
        var f = function(c, d) {
            a(d).css(b, "")
        };
        for (var g in e) {
            var h = e[g];
            a(h).find("*").each(f)
        }
        this.$element.find('span[data-font="' + b + '"] > span[data-font="' + b + '"]').each(function(c, d) {
            a(d).attr("style") && (a(d).before('<span class="close-span"></span>'), a(d).after('<span data-font="' + b + '" style="' + b + ": " + a(d).parent().css(b) + ';" data-open="true"></span>'))
        });
        var i = this.$element.html();
        i = i.replace(new RegExp('<span class="close-span"></span>', "g"), "</span>"), i = i.replace(new RegExp('data-open="true"></span>', "g"), ">"), this.$element.html(i), this.beautifyFont(b), this.$element.find('span[style=""]').each(function(b, c) {
            a(c).replaceWith(a(c).html())
        }), this.$element.find('span[data-font="' + b + '"]').each(function(c, d) {
            a(d).css(b) == a(d).parent().css(b) && a(d).replaceWith(a(d).html())
        }), this.$element.find('span[data-font="' + b + '"]').each(function(b, c) {
            "" === a(c).text() && a(c).replaceWith(a(c).html())
        }), this.beautifyFont(b), this.restoreSelectionByMarkers(), this.repositionEditor(), this.callback(c, [d])
    }, b.prototype.backColor = function(c) {
        var d = "backColor";
        this.browser.msie || (d = "hiliteColor");
        var e = a(this.getSelectionElement()).css("background-color");
        document.execCommand(d, !1, c);
        var f = this.$editor.find('button.fr-color-bttn[data-cmd="backColor"][data-val="' + c + '"]');
        f.addClass("active"), f.siblings().removeClass("active"), this.callback("backColor", [b.hexToRGBString(c), e])
    }, b.prototype.foreColor = function(c) {
        var d = a(this.getSelectionElement()).css("color");
        document.execCommand("foreColor", !1, c), this.saveSelectionByMarkers(), this.$element.find("font[color]").each(function(b, d) {
            a(d).replaceWith(a("<span>").css("color", c).html(a(d).html()))
        }), this.restoreSelectionByMarkers();
        var e = this.$editor.find('button.fr-color-bttn[data-cmd="foreColor"][data-val="' + c + '"]');
        e.addClass("active"), e.siblings().removeClass("active"), this.callback("foreColor", [b.hexToRGBString(c), d])
    }, b.prototype.formatBlock = function(b, c) {
        if (this.disabledList.indexOf("formatBlock") >= 0) return !1;
        this.saveSelectionByMarkers(), this.wrapText(), this.restoreSelectionByMarkers();
        var d = this.getSelectionElements();
        this.saveSelectionByMarkers();
        var e;
        for (var f in d) {
            var g = a(d[f]);
            if (!this.fakeEmpty(g))
                if (e = "n" == b ? this.options.paragraphy ? a("<div>").html(g.html()) : g.html() + "<br/>" : a("<" + b + ">").html(g.html()), g.get(0) != this.$element.get(0) && "LI" != g.get(0).tagName) {
                    var h = g.prop("attributes");
                    if (e.attr)
                        for (var i in h) "class" !== h[i].name && e.attr(h[i].name, h[i].value);
                    var j = this.options.blockStyles[b];
                    void 0 === j && (j = this.options.defaultBlockStyle);
                    try {
                        if (void 0 !== g.attr("class") && void 0 !== j)
                            for (var k = g.attr("class").split(" "), l = 0; l < k.length; l++) {
                                var m = k[l];
                                void 0 !== j[m] && void 0 === c && e.addClass(m)
                            } else e.addClass(g.attr("class"));
                        "*" != c && e.addClass(c)
                    } catch (n) {}
                    g.replaceWith(e)
                } else g.html(e)
        }
        this.unwrapText(), this.restoreSelectionByMarkers(), this.hide(), this.callback("formatBlock")
    }, b.prototype.blockStyle = function(a) {
        var b = this.activeBlockTag();
        this.formatBlock(b, a)
    }, b.prototype.formatList = function(b) {
        this.saveSelectionByMarkers();
        var c, d = this.getSelectionElements(),
            e = !0,
            f = !1;
        for (var g in d)
            if (c = a(d[g]), c.parents("li").length > 0 || "LI" == c.get(0).tagName) {
                var h;
                h = "LI" == c.get(0).tagName ? c : c.parents("li"), c.parents("ol").length > 0 ? (h.before('<span class="close-ol"></span>'), h.after('<span class="open-ol"></span>')) : c.parents("ul").length > 0 && (h.before('<span class="close-ul"></span>'), h.after('<span class="open-ul"></span>')), h.replaceWith(h.contents()), f = !0
            } else e = !1;
        if (f) {
            var i = this.$element.html();
            i = i.replace(new RegExp('<span class="close-ul"></span>', "g"), "</ul>"), i = i.replace(new RegExp('<span class="open-ul"></span>', "g"), "<ul>"), i = i.replace(new RegExp('<span class="close-ol"></span>', "g"), "</ol>"), i = i.replace(new RegExp('<span class="open-ol"></span>', "g"), "<ol>"), this.$element.html(i), this.$element.find("ul:empty, ol:empty").remove()
        }
        if (this.clearSelection(), e === !1) {
            this.wrapText(), this.restoreSelectionByMarkers(), d = this.getSelectionElements(), this.saveSelectionByMarkers();
            var j = a("<ol>");
            "insertUnorderedList" == b && (j = a("<ul>"));
            for (var k in d) c = a(d[k]), c.get(0) != this.$element.get(0) && (j.append(a("<li>").append(c.clone())), k != d.length - 1 ? c.remove() : (c.replaceWith(j), j.find("li")));
            this.unwrapText()
        }
        this.restoreSelectionByMarkers(), this.repositionEditor(), this.callback(b)
    }, b.prototype.align = function(b) {
        var c = this.getSelectionElements();
        "justifyLeft" == b ? b = "left" : "justifyRight" == b ? b = "right" : "justifyCenter" == b ? b = "center" : "justifyFull" == b && (b = "justify");
        for (var d in c) a(c[d]).css("text-align", b);
        this.repositionEditor(), this.callback("align", [b])
    }, b.prototype.indent = function(b) {
        var c = 20;
        b && (c = -20), this.saveSelectionByMarkers(), this.wrapText(), this.restoreSelectionByMarkers();
        var d = this.getSelectionElements();
        this.saveSelectionByMarkers();
        for (var e in d) {
            var f = a(d[e]);
            if (f.parentsUntil(this.$element, "li").length > 0 && (f = f.parentsUntil(this.$element, "li")), f.get(0) != this.$element.get(0)) {
                var g = parseInt(f.css("margin-left").replace(/px/, ""), 10),
                    h = Math.max(0, g + c);
                f.css("marginLeft", h), "LI" === f.get(0).tagName && (h % 60 === 0 ? 0 === f.parents("ol").length ? f.css("list-style-type", "disc") : f.css("list-style-type", "decimal") : h % 40 === 0 ? 0 === f.parents("ol").length ? f.css("list-style-type", "square") : f.css("list-style-type", "lower-latin") : 0 === f.parents("ol").length ? f.css("list-style-type", "circle") : f.css("list-style-type", "lower-roman"))
            } else {
                var i = a("<div>").html(f.html());
                f.html(i), i.css("marginLeft", Math.max(0, c))
            }
        }
        this.unwrapText(), this.restoreSelectionByMarkers(), this.repositionEditor(), b || this.callback("indent")
    }, b.prototype.outdent = function() {
        this.indent(!0), this.callback("outdent")
    }, b.prototype.insertLink = function() {
        this.showInsertLink(), this.options.inlineMode || this.positionPopup("createLink"), this.saveSelection();
        var b = this.getSelectionLink(),
            c = this.getSelectionLinks();
        c.length > 0 ? this.$link_wrapper.find('input[type="checkbox"]').prop("checked", "_blank" == a(c[0]).attr("target")) : this.$link_wrapper.find('input[type="checkbox"]').prop("checked", this.options.alwaysBlank), this.$link_wrapper.find('input[type="text"]').val(b || "http://")
    }, b.prototype.insertImage = function() {
        this.showInsertImage(), this.saveSelection(), this.options.inlineMode || this.positionPopup("insertImage"), this.$image_wrapper.find('input[type="text"]').val("")
    }, b.prototype.insertVideo = function() {
        this.showInsertVideo(), this.saveSelection(), this.options.inlineMode || this.positionPopup("insertVideo"), this.$video_wrapper.find("textarea").val("")
    }, b.prototype.execDefault = function(a, b) {
        document.execCommand(a, !1, b), "insertOrderedList" == a ? this.$bttn_wrapper.find('[data-cmd="insertUnorderedList"]').removeClass("active") : "insertUnorderedList" == a && this.$bttn_wrapper.find('[data-cmd="insertOrderedList"]').removeClass("active"), this.callback(a)
    }, b.prototype.isActive = function(a, b) {
        switch (a) {
            case "fontFamily":
                return this._isActiveFontFamily(b);
            case "fontSize":
                return this._isActiveFontSize(b);
            case "backColor":
                return this._isActiveBackColor(b);
            case "foreColor":
                return this._isActiveForeColor(b);
            case "formatBlock":
                return this._isActiveFormatBlock(b);
            case "blockStyle":
                return this._isActiveBlockStyle(b);
            case "createLink":
            case "insertImage":
                return !1;
            case "justifyLeft":
            case "justifyRight":
            case "justifyCenter":
            case "justifyFull":
                return this._isActiveAlign(a);
            case "html":
                return this._isActiveHTML();
            case "undo":
            case "redo":
            case "save":
                return !1;
            default:
                return this._isActiveDefault(a)
        }
    }, b.prototype._isActiveFontFamily = function(b) {
        var c = this.getSelectionElement();
        return a(c).css("fontFamily").replace(/ /g, "") === b.replace(/ /g, "") ? !0 : !1
    }, b.prototype._isActiveFontSize = function(b) {
        var c = this.getSelectionElement();
        return a(c).css("fontSize") === b ? !0 : !1
    }, b.prototype._isActiveBackColor = function(b) {
        for (var c = this.getSelectionElement(); a(c).get(0) != this.$element.get(0);) {
            if (a(c).css("background-color") === b) return !0;
            if ("transparent" != a(c).css("background-color") && "rgba(0, 0, 0, 0)" != a(c).css("background-color")) return !1;
            c = a(c).parent()
        }
        return !1
    }, b.prototype._isActiveForeColor = function(a) {
        return document.queryCommandValue("foreColor") === a ? !0 : !1
    }, b.prototype._isActiveFormatBlock = function(b) {
        "CODE" === b.toUpperCase() ? b = "PRE" : "N" === b.toUpperCase() && (b = "DIV");
        for (var c = a(this.getSelectionElement()); c.get(0) != this.$element.get(0);) {
            if (c.get(0).tagName == b.toUpperCase()) return !0;
            c = c.parent()
        }
        return !1
    }, b.prototype._isActiveBlockStyle = function(b) {
        for (var c = a(this.getSelectionElement()); c.get(0) != this.$element.get(0);) {
            if (c.hasClass(b)) return !0;
            c = c.parent()
        }
        return !1
    }, b.prototype._isActiveAlign = function(b) {
        var c = this.getSelectionElements();
        return "justifyLeft" == b ? b = "left" : "justifyRight" == b ? b = "right" : "justifyCenter" == b ? b = "center" : "justifyFull" == b && (b = "justify"), b == a(c[0]).css("text-align") ? !0 : !1
    }, b.prototype._isActiveHTML = function() {
        return this.isHTML ? !0 : !1
    }, b.prototype._isActiveDefault = function(a) {
        try {
            if (document.queryCommandState(a) === !0) return !0
        } catch (b) {}
        return !1
    }, b.prototype.activeBlockTag = function() {
        return a('.active[data-cmd="formatBlock"]').data("val")
    }, b.prototype.updateBlockStyles = function() {
        var b = this.activeBlockTag();
        this.$bttn_wrapper.find(".fr-block-style").empty(), this.$bttn_wrapper.find(".fr-block-style").append(a('<li data-cmd="blockStyle" data-val="*">').append(a('<a href="#" data-text="true">').text("Default")));
        var c = this.options.blockStyles[b];
        if (void 0 === c && (c = this.options.defaultBlockStyle), void 0 !== c)
            for (var d in c) {
                var e = c[d];
                this.$bttn_wrapper.find(".fr-block-style").append(a("<li>").append(a('<a href="#" data-text="true">').text(e).addClass(d)).attr("data-cmd", "blockStyle").attr("data-val", d))
            }
        this.bindCommandEvents(this.$bttn_wrapper.find(".fr-block-style [data-cmd]"))
    }, b.prototype.refreshButtons = function() {
        return !this.selectionInEditor() || this.isHTML ? !1 : (this.refreshUndoRedo(), this.$bttn_wrapper.find('[data-cmd="formatBlock"]').each(a.proxy(function(a, b) {
            this.refreshFormatBlock(b)
        }, this)), this.updateBlockStyles(), this.$bttn_wrapper.find("[data-cmd]").not('[data-cmd="formatBlock"]').each(a.proxy(function(b, c) {
            switch (a(c).data("cmd")) {
                case "fontSize":
                    this.refreshFontSize(c);
                    break;
                case "fontFamily":
                    this.refreshFontFamily(c);
                    break;
                case "backColor":
                    this.refreshBackColor(c);
                    break;
                case "foreColor":
                    this.refreshForeColor(c);
                    break;
                case "formatBlock":
                    this.refreshFormatBlock(c);
                    break;
                case "blockStyle":
                    this.refreshBlockStyle(c);
                    break;
                case "createLink":
                case "insertImage":
                    break;
                case "justifyLeft":
                case "justifyRight":
                case "justifyCenter":
                case "justifyFull":
                    this.refreshAlign(c);
                    break;
                case "html":
                    this.isActive("html") ? a(c).addClass("active") : a(c).removeClass("active");
                    break;
                case "undo":
                case "redo":
                case "save":
                    break;
                default:
                    this.refreshDefault(c)
            }
        }, this)), void(0 === this.$bttn_wrapper.find(".fr-block-style .active").length && this.$bttn_wrapper.find(".fr-block-style li:first").addClass("active")))
    }, b.prototype.refreshBlockStyle = function(b) {
        this.disabledList.indexOf("blockStyle") >= 0 && a(b).parents(".fr-dropdown").attr("data-disabled", !0), a(b).removeClass("active"), this.isActive(a(b).data("cmd"), a(b).data("val")) && a(b).addClass("active")
    }, b.prototype.refreshFormatBlock = function(b) {
        this.disabledList.indexOf("formatBlock") >= 0 && a(b).parents(".fr-dropdown").attr("data-disabled", !0), a(b).removeClass("active"), this.isActive(a(b).data("cmd"), a(b).data("val")) && a(b).addClass("active")
    }, b.prototype.refreshUndoRedo = function() {
        if (this.isEnabled("undo") || this.isEnabled("redo")) {
            if (void 0 === this.$editor) return;
            this.$bttn_wrapper.find('[data-cmd="undo"], [data-cmd="redo"]').prop("disabled", !1), (0 === this.undoStack.length || this.undoIndex <= 1 || this.isHTML) && this.$bttn_wrapper.find('[data-cmd="undo"]').prop("disabled", !0), (this.undoIndex == this.undoStack.length || this.isHTML) && this.$bttn_wrapper.find('[data-cmd="redo"]').prop("disabled", !0)
        }
    }, b.prototype.refreshDefault = function(b) {
        a(b).removeClass("active"), this.isActive(a(b).data("cmd")) && a(b).addClass("active")
    }, b.prototype.refreshAlign = function(b) {
        var c = a(b).data("cmd");
        this.isActive(c) && (a(b).parents("ul").find(".fr-bttn").removeClass("active"), a(b).addClass("active"), a(b).parents(".fr-dropdown").find(".fr-trigger").html(a(b).html()))
    }, b.prototype.refreshForeColor = function(b) {
        a(b).removeClass("active"), this.isActive("foreColor", b.style.backgroundColor) && a(b).addClass("active")
    }, b.prototype.refreshBackColor = function(b) {
        a(b).removeClass("active"), this.isActive("backColor", b.style.backgroundColor) && a(b).addClass("active")
    }, b.prototype.refreshFontSize = function(b) {
        a(b).removeClass("active"), this.isActive("fontSize", a(b).data("val")) && a(b).addClass("active")
    }, b.prototype.refreshFontFamily = function(b) {
        a(b).removeClass("active"), this.isActive("fontFamily", a(b).data("val")) && a(b).addClass("active")
    }, b.prototype.option = function(b, c) {
        if (void 0 === b) return this.options;
        if (b instanceof Object) this.options = a.extend({}, this.options, b), this.initOptions(), this.setCustomText(), this.setLanguage();
        else {
            if (void 0 === c) return this.options[b];
            switch (this.options[b] = c, b) {
                case "borderColor":
                    this.setBorderColor();
                    break;
                case "direction":
                    this.setDirection();
                    break;
                case "height":
                case "width":
                case "minHeight":
                    this.setDimensions();
                    break;
                case "spellcheck":
                    this.setSpellcheck();
                    break;
                case "placeholder":
                    this.setPlaceholder();
                    break;
                case "customText":
                    this.setCustomText();
                    break;
                case "inverseSkin":
                    this.setInverseSkin();
                    break;
                case "language":
                    this.setLanguage();
                    break;
                case "textNearImage":
                    this.setTextNearImage();
                    break;
                case "zIndex":
                    this.setZIndex()
            }
        }
    };
    var c = a.fn.editable;
    a.fn.editable = function(c) {
        for (var d = [], e = 0; e < arguments.length; e++) d.push(arguments[e]);
        if (a("html").data("editable", !0), "string" == typeof c) {
            var f = [];
            return this.each(function() {
                var b = a(this),
                    e = b.data("fa.editable"),
                    g = e[c].apply(e, d.slice(1));
                f.push(void 0 === g ? this : g)
            }), f
        }
        return this.each(function() {
            var d = a(this),
                e = d.data("fa.editable");
            e || d.data("fa.editable", e = new b(this, c))
        })
    }, a.fn.editable.Constructor = b, a.Editable = b, a.fn.editable.noConflict = function() {
        return a.fn.editable = c, this
    }
}(window.jQuery),
function(a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
}(function(a, b) {
    function c(a) {
        function b() {
            d ? (c(), M(b), e = !0, d = !1) : e = !1
        }
        var c = a,
            d = !1,
            e = !1;
        this.kick = function() {
            d = !0, e || b()
        }, this.end = function(a) {
            var b = c;
            a && (e ? (c = d ? function() {
                b(), a()
            } : a, d = !0) : a())
        }
    }

    function d() {
        return !0
    }

    function e() {
        return !1
    }

    function f(a) {
        a.preventDefault()
    }

    function g(a) {
        N[a.target.tagName.toLowerCase()] || a.preventDefault()
    }

    function h(a) {
        return 1 === a.which && !a.ctrlKey && !a.altKey
    }

    function i(a, b) {
        var c, d;
        if (a.identifiedTouch) return a.identifiedTouch(b);
        for (c = -1, d = a.length; ++c < d;)
            if (a[c].identifier === b) return a[c]
    }

    function j(a, b) {
        var c = i(a.changedTouches, b.identifier);
        if (c && (c.pageX !== b.pageX || c.pageY !== b.pageY)) return c
    }

    function k(a) {
        var b;
        h(a) && (b = {
            target: a.target,
            startX: a.pageX,
            startY: a.pageY,
            timeStamp: a.timeStamp
        }, J(document, O.move, l, b), J(document, O.cancel, m, b))
    }

    function l(a) {
        var b = a.data;
        s(a, b, a, n)
    }

    function m() {
        n()
    }

    function n() {
        K(document, O.move, l), K(document, O.cancel, m)
    }

    function o(a) {
        var b, c;
        N[a.target.tagName.toLowerCase()] || (b = a.changedTouches[0], c = {
            target: b.target,
            startX: b.pageX,
            startY: b.pageY,
            timeStamp: a.timeStamp,
            identifier: b.identifier
        }, J(document, P.move + "." + b.identifier, p, c), J(document, P.cancel + "." + b.identifier, q, c))
    }

    function p(a) {
        var b = a.data,
            c = j(a, b);
        c && s(a, b, c, r)
    }

    function q(a) {
        var b = a.data,
            c = i(a.changedTouches, b.identifier);
        c && r(b.identifier)
    }

    function r(a) {
        K(document, "." + a, p), K(document, "." + a, q)
    }

    function s(a, b, c, d) {
        var e = c.pageX - b.startX,
            f = c.pageY - b.startY;
        I * I > e * e + f * f || v(a, b, c, e, f, d)
    }

    function t() {
        return this._handled = d, !1
    }

    function u(a) {
        try {
            a._handled()
        } catch (b) {
            return !1
        }
    }

    function v(a, b, c, d, e, f) {
        {
            var g, h;
            b.target
        }
        g = a.targetTouches, h = a.timeStamp - b.timeStamp, b.type = "movestart", b.distX = d, b.distY = e, b.deltaX = d, b.deltaY = e, b.pageX = c.pageX, b.pageY = c.pageY, b.velocityX = d / h, b.velocityY = e / h, b.targetTouches = g, b.finger = g ? g.length : 1, b._handled = t, b._preventTouchmoveDefault = function() {
            a.preventDefault()
        }, L(b.target, b), f(b.identifier)
    }

    function w(a) {
        var b = a.data.timer;
        a.data.touch = a, a.data.timeStamp = a.timeStamp, b.kick()
    }

    function x(a) {
        var b = a.data.event,
            c = a.data.timer;
        y(), D(b, c, function() {
            setTimeout(function() {
                K(b.target, "click", e)
            }, 0)
        })
    }

    function y() {
        K(document, O.move, w), K(document, O.end, x)
    }

    function z(a) {
        var b = a.data.event,
            c = a.data.timer,
            d = j(a, b);
        d && (a.preventDefault(), b.targetTouches = a.targetTouches, a.data.touch = d, a.data.timeStamp = a.timeStamp, c.kick())
    }

    function A(a) {
        var b = a.data.event,
            c = a.data.timer,
            d = i(a.changedTouches, b.identifier);
        d && (B(b), D(b, c))
    }

    function B(a) {
        K(document, "." + a.identifier, z), K(document, "." + a.identifier, A)
    }

    function C(a, b, c) {
        var d = c - a.timeStamp;
        a.type = "move", a.distX = b.pageX - a.startX, a.distY = b.pageY - a.startY, a.deltaX = b.pageX - a.pageX, a.deltaY = b.pageY - a.pageY, a.velocityX = .3 * a.velocityX + .7 * a.deltaX / d, a.velocityY = .3 * a.velocityY + .7 * a.deltaY / d, a.pageX = b.pageX, a.pageY = b.pageY
    }

    function D(a, b, c) {
        b.end(function() {
            return a.type = "moveend", L(a.target, a), c && c()
        })
    }

    function E() {
        return J(this, "movestart.move", u), !0
    }

    function F() {
        return K(this, "dragstart drag", f), K(this, "mousedown touchstart", g), K(this, "movestart", u), !0
    }

    function G(a) {
        "move" !== a.namespace && "moveend" !== a.namespace && (J(this, "dragstart." + a.guid + " drag." + a.guid, f, b, a.selector), J(this, "mousedown." + a.guid, g, b, a.selector))
    }

    function H(a) {
        "move" !== a.namespace && "moveend" !== a.namespace && (K(this, "dragstart." + a.guid + " drag." + a.guid), K(this, "mousedown." + a.guid))
    }
    var I = 6,
        J = a.event.add,
        K = a.event.remove,
        L = function(b, c, d) {
            a.event.trigger(c, d, b)
        },
        M = function() {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(a) {
                return window.setTimeout(function() {
                    a()
                }, 25)
            }
        }(),
        N = {
            textarea: !0,
            input: !0,
            select: !0,
            button: !0
        },
        O = {
            move: "mousemove",
            cancel: "mouseup dragstart",
            end: "mouseup"
        },
        P = {
            move: "touchmove",
            cancel: "touchend",
            end: "touchend"
        };
    a.event.special.movestart = {
        setup: E,
        teardown: F,
        add: G,
        remove: H,
        _default: function(a) {
            function d() {
                C(f, g.touch, g.timeStamp), L(a.target, f)
            }
            var f, g;
            a._handled() && (f = {
                target: a.target,
                startX: a.startX,
                startY: a.startY,
                pageX: a.pageX,
                pageY: a.pageY,
                distX: a.distX,
                distY: a.distY,
                deltaX: a.deltaX,
                deltaY: a.deltaY,
                velocityX: a.velocityX,
                velocityY: a.velocityY,
                timeStamp: a.timeStamp,
                identifier: a.identifier,
                targetTouches: a.targetTouches,
                finger: a.finger
            }, g = {
                event: f,
                timer: new c(d),
                touch: b,
                timeStamp: b
            }, a.identifier === b ? (J(a.target, "click", e), J(document, O.move, w, g), J(document, O.end, x, g)) : (a._preventTouchmoveDefault(), J(document, P.move + "." + a.identifier, z, g), J(document, P.end + "." + a.identifier, A, g)))
        }
    }, a.event.special.move = {
        setup: function() {
            J(this, "movestart.move", a.noop)
        },
        teardown: function() {
            K(this, "movestart.move", a.noop)
        }
    }, a.event.special.moveend = {
        setup: function() {
            J(this, "movestart.moveend", a.noop)
        },
        teardown: function() {
            K(this, "movestart.moveend", a.noop)
        }
    }, J(document, "mousedown.move", k), J(document, "touchstart.move", o), "function" == typeof Array.prototype.indexOf && ! function(a) {
        for (var b = ["changedTouches", "targetTouches"], c = b.length; c--;) - 1 === a.event.props.indexOf(b[c]) && a.event.props.push(b[c])
    }(a)
}), window.WYSIWYGModernizr = function(a, b, c) {
        function d(a) {
            n.cssText = a
        }

        function e(a, b) {
            return typeof a === b
        }
        var f, g, h, i = "2.7.1",
            j = {},
            k = b.documentElement,
            l = "modernizr",
            m = b.createElement(l),
            n = m.style,
            o = ({}.toString, " -webkit- -moz- -o- -ms- ".split(" ")),
            p = {},
            q = [],
            r = q.slice,
            s = function(a, c, d, e) {
                var f, g, h, i, j = b.createElement("div"),
                    m = b.body,
                    n = m || b.createElement("body");
                if (parseInt(d, 10))
                    for (; d--;) h = b.createElement("div"), h.id = e ? e[d] : l + (d + 1), j.appendChild(h);
                return f = ["&#173;", '<style id="s', l, '">', a, "</style>"].join(""), j.id = l, (m ? j : n).innerHTML += f, n.appendChild(j), m || (n.style.background = "", n.style.overflow = "hidden", i = k.style.overflow, k.style.overflow = "hidden", k.appendChild(n)), g = c(j, a), m ? j.parentNode.removeChild(j) : (n.parentNode.removeChild(n), k.style.overflow = i), !!g
            },
            t = function(b) {
                var c = a.matchMedia || a.msMatchMedia;
                if (c) return c(b).matches;
                var d;
                return s("@media " + b + " { #" + l + " { position: absolute; } }", function(b) {
                    d = "absolute" == (a.getComputedStyle ? getComputedStyle(b, null) : b.currentStyle).position
                }), d
            },
            u = {}.hasOwnProperty;
        h = e(u, "undefined") || e(u.call, "undefined") ? function(a, b) {
            return b in a && e(a.constructor.prototype[b], "undefined")
        } : function(a, b) {
            return u.call(a, b)
        }, Function.prototype.bind || (Function.prototype.bind = function(a) {
            var b = this;
            if ("function" != typeof b) throw new TypeError;
            var c = r.call(arguments, 1),
                d = function() {
                    if (this instanceof d) {
                        var e = function() {};
                        e.prototype = b.prototype;
                        var f = new e,
                            g = b.apply(f, c.concat(r.call(arguments)));
                        return Object(g) === g ? g : f
                    }
                    return b.apply(a, c.concat(r.call(arguments)))
                };
            return d
        }), p.touch = function() {
            var c;
            return "ontouchstart" in a || a.DocumentTouch && b instanceof DocumentTouch ? c = !0 : s(["@media (", o.join("touch-enabled),("), l, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(a) {
                c = 9 === a.offsetTop
            }), c
        };
        for (var v in p) h(p, v) && (g = v.toLowerCase(), j[g] = p[v](), q.push((j[g] ? "" : "no-") + g));
        return j.addTest = function(a, b) {
            if ("object" == typeof a)
                for (var d in a) h(a, d) && j.addTest(d, a[d]);
            else {
                if (a = a.toLowerCase(), j[a] !== c) return j;
                b = "function" == typeof b ? b() : b, "undefined" != typeof enableClasses && enableClasses && (k.className += " " + (b ? "" : "no-") + a), j[a] = b
            }
            return j
        }, d(""), m = f = null, j._version = i, j._prefixes = o, j.mq = t, j.testStyles = s, j
    }(this, this.document),
    function() {
        function a(a) {
            return a.replace(/^\s+|\s+$/g, "")
        }

        function b(a) {
            return a.replace(/^\s+/g, "")
        }

        function c(c, d, e, f) {
            function g() {
                return this.pos = 0, this.token = "", this.current_mode = "CONTENT", this.tags = {
                    parent: "parent1",
                    parentcount: 1,
                    parent1: ""
                }, this.tag_type = "", this.token_text = this.last_token = this.last_text = this.token_type = "", this.newlines = 0, this.indent_content = i, this.Utils = {
                    whitespace: "\n\r	 ".split(""),
                    single_token: "br,input,link,meta,!doctype,basefont,base,area,hr,wbr,param,img,isindex,?xml,embed,?php,?,?=".split(","),
                    extra_liners: "head,body,/html".split(","),
                    in_array: function(a, b) {
                        for (var c = 0; c < b.length; c++)
                            if (a === b[c]) return !0;
                        return !1
                    }
                }, this.traverse_whitespace = function() {
                    var a = "";
                    if (a = this.input.charAt(this.pos), this.Utils.in_array(a, this.Utils.whitespace)) {
                        for (this.newlines = 0; this.Utils.in_array(a, this.Utils.whitespace);) o && "\n" === a && this.newlines <= p && (this.newlines += 1), this.pos++, a = this.input.charAt(this.pos);
                        return !0
                    }
                    return !1
                }, this.get_content = function() {
                    for (var a = "", b = [], c = !1;
                        "<" !== this.input.charAt(this.pos);) {
                        if (this.pos >= this.input.length) return b.length ? b.join("") : ["", "TK_EOF"];
                        if (this.traverse_whitespace()) b.length && (c = !0);
                        else {
                            if (q) {
                                var d = this.input.substr(this.pos, 3);
                                if ("{{#" === d || "{{/" === d) break;
                                if ("{{" === this.input.substr(this.pos, 2) && "{{else}}" === this.get_tag(!0)) break
                            }
                            a = this.input.charAt(this.pos), this.pos++, c && (this.line_char_count >= this.wrap_line_length ? (this.print_newline(!1, b), this.print_indentation(b)) : (this.line_char_count++, b.push(" ")), c = !1), this.line_char_count++, b.push(a)
                        }
                    }
                    return b.length ? b.join("") : ""
                }, this.get_contents_to = function(a) {
                    if (this.pos === this.input.length) return ["", "TK_EOF"];
                    var b = "",
                        c = new RegExp("</" + a + "\\s*>", "igm");
                    c.lastIndex = this.pos;
                    var d = c.exec(this.input),
                        e = d ? d.index : this.input.length;
                    return this.pos < e && (b = this.input.substring(this.pos, e), this.pos = e), b
                }, this.record_tag = function(a) {
                    this.tags[a + "count"] ? (this.tags[a + "count"]++, this.tags[a + this.tags[a + "count"]] = this.indent_level) : (this.tags[a + "count"] = 1, this.tags[a + this.tags[a + "count"]] = this.indent_level), this.tags[a + this.tags[a + "count"] + "parent"] = this.tags.parent, this.tags.parent = a + this.tags[a + "count"]
                }, this.retrieve_tag = function(a) {
                    if (this.tags[a + "count"]) {
                        for (var b = this.tags.parent; b && a + this.tags[a + "count"] !== b;) b = this.tags[b + "parent"];
                        b && (this.indent_level = this.tags[a + this.tags[a + "count"]], this.tags.parent = this.tags[b + "parent"]), delete this.tags[a + this.tags[a + "count"] + "parent"], delete this.tags[a + this.tags[a + "count"]], 1 === this.tags[a + "count"] ? delete this.tags[a + "count"] : this.tags[a + "count"]--
                    }
                }, this.indent_to_tag = function(a) {
                    if (this.tags[a + "count"]) {
                        for (var b = this.tags.parent; b && a + this.tags[a + "count"] !== b;) b = this.tags[b + "parent"];
                        b && (this.indent_level = this.tags[a + this.tags[a + "count"]])
                    }
                }, this.get_tag = function(a) {
                    var b, c, d, e = "",
                        f = [],
                        g = "",
                        h = !1,
                        i = this.pos,
                        j = this.line_char_count;
                    a = void 0 !== a ? a : !1;
                    do {
                        if (this.pos >= this.input.length) return a && (this.pos = i, this.line_char_count = j), f.length ? f.join("") : ["", "TK_EOF"];
                        if (e = this.input.charAt(this.pos), this.pos++, this.Utils.in_array(e, this.Utils.whitespace)) h = !0;
                        else {
                            if (("'" === e || '"' === e) && (e += this.get_unformatted(e), h = !0), "=" === e && (h = !1), f.length && "=" !== f[f.length - 1] && ">" !== e && h && (this.line_char_count >= this.wrap_line_length ? (this.print_newline(!1, f), this.print_indentation(f)) : (f.push(" "), this.line_char_count++), h = !1), q && "<" === d && e + this.input.charAt(this.pos) === "{{" && (e += this.get_unformatted("}}"), f.length && " " !== f[f.length - 1] && "<" !== f[f.length - 1] && (e = " " + e), h = !0), "<" !== e || d || (b = this.pos - 1, d = "<"), q && !d && f.length >= 2 && "{" === f[f.length - 1] && "{" == f[f.length - 2] && (b = "#" === e || "/" === e ? this.pos - 3 : this.pos - 2, d = "{"), this.line_char_count++, f.push(e), f[1] && "!" === f[1]) {
                                f = [this.get_comment(b)];
                                break
                            }
                            if (q && "{" === d && f.length > 2 && "}" === f[f.length - 2] && "}" === f[f.length - 1]) break
                        }
                    } while (">" !== e);
                    var k, l, m = f.join("");
                    k = m.indexOf(-1 !== m.indexOf(" ") ? " " : "{" === m[0] ? "}" : ">"), l = "<" !== m[0] && q ? "#" === m[2] ? 3 : 2 : 1;
                    var o = m.substring(l, k).toLowerCase();
                    return "/" === m.charAt(m.length - 2) || this.Utils.in_array(o, this.Utils.single_token) ? a || (this.tag_type = "SINGLE") : q && "{" === m[0] && "else" === o ? a || (this.indent_to_tag("if"), this.tag_type = "HANDLEBARS_ELSE", this.indent_content = !0, this.traverse_whitespace()) : "script" === o ? a || (this.record_tag(o), this.tag_type = "SCRIPT") : "style" === o ? a || (this.record_tag(o), this.tag_type = "STYLE") : this.is_unformatted(o, n) ? (g = this.get_unformatted("</" + o + ">", m), f.push(g), b > 0 && this.Utils.in_array(this.input.charAt(b - 1), this.Utils.whitespace) && f.splice(0, 0, this.input.charAt(b - 1)), c = this.pos - 1, this.Utils.in_array(this.input.charAt(c + 1), this.Utils.whitespace) && f.push(this.input.charAt(c + 1)), this.tag_type = "SINGLE") : "!" === o.charAt(0) ? a || (this.tag_type = "SINGLE", this.traverse_whitespace()) : a || ("/" === o.charAt(0) ? (this.retrieve_tag(o.substring(1)), this.tag_type = "END", this.traverse_whitespace()) : (this.record_tag(o), "html" !== o.toLowerCase() && (this.indent_content = !0), this.tag_type = "START", this.traverse_whitespace()), this.Utils.in_array(o, this.Utils.extra_liners) && (this.print_newline(!1, this.output), this.output.length && "\n" !== this.output[this.output.length - 2] && this.print_newline(!0, this.output))), a && (this.pos = i, this.line_char_count = j), f.join("")
                }, this.get_comment = function(a) {
                    var b = "",
                        c = ">",
                        d = !1;
                    for (this.pos = a, input_char = this.input.charAt(this.pos), this.pos++; this.pos <= this.input.length && (b += input_char, b[b.length - 1] !== c[c.length - 1] || -1 === b.indexOf(c));) !d && b.length < 10 && (0 === b.indexOf("<![if") ? (c = "<![endif]>", d = !0) : 0 === b.indexOf("<![cdata[") ? (c = "]]>", d = !0) : 0 === b.indexOf("<![") ? (c = "]>", d = !0) : 0 === b.indexOf("<!--") && (c = "-->", d = !0)), input_char = this.input.charAt(this.pos), this.pos++;
                    return b
                }, this.get_unformatted = function(a, b) {
                    if (b && -1 !== b.toLowerCase().indexOf(a)) return "";
                    var c = "",
                        d = "",
                        e = 0,
                        f = !0;
                    do {
                        if (this.pos >= this.input.length) return d;
                        if (c = this.input.charAt(this.pos), this.pos++, this.Utils.in_array(c, this.Utils.whitespace)) {
                            if (!f) {
                                this.line_char_count--;
                                continue
                            }
                            if ("\n" === c || "\r" === c) {
                                d += "\n", this.line_char_count = 0;
                                continue
                            }
                        }
                        d += c, this.line_char_count++, f = !0, q && "{" === c && d.length && "{" === d[d.length - 2] && (d += this.get_unformatted("}}"), e = d.length)
                    } while (-1 === d.toLowerCase().indexOf(a, e));
                    return d
                }, this.get_token = function() {
                    var a;
                    if ("TK_TAG_SCRIPT" === this.last_token || "TK_TAG_STYLE" === this.last_token) {
                        var b = this.last_token.substr(7);
                        return a = this.get_contents_to(b), "string" != typeof a ? a : [a, "TK_" + b]
                    }
                    if ("CONTENT" === this.current_mode) return a = this.get_content(), "string" != typeof a ? a : [a, "TK_CONTENT"];
                    if ("TAG" === this.current_mode) {
                        if (a = this.get_tag(), "string" != typeof a) return a;
                        var c = "TK_TAG_" + this.tag_type;
                        return [a, c]
                    }
                }, this.get_full_indent = function(a) {
                    return a = this.indent_level + a || 0, 1 > a ? "" : Array(a + 1).join(this.indent_string)
                }, this.is_unformatted = function(a, b) {
                    if (!this.Utils.in_array(a, b)) return !1;
                    if ("a" !== a.toLowerCase() || !this.Utils.in_array("a", b)) return !0;
                    var c = this.get_tag(!0),
                        d = (c || "").match(/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/);
                    return !d || this.Utils.in_array(d, b) ? !0 : !1
                }, this.printer = function(a, c, d, e, f) {
                    this.input = a || "", this.output = [], this.indent_character = c, this.indent_string = "", this.indent_size = d, this.brace_style = f, this.indent_level = 0, this.wrap_line_length = e, this.line_char_count = 0;
                    for (var g = 0; g < this.indent_size; g++) this.indent_string += this.indent_character;
                    this.print_newline = function(a, b) {
                        this.line_char_count = 0, b && b.length && (a || "\n" !== b[b.length - 1]) && b.push("\n")
                    }, this.print_indentation = function(a) {
                        for (var b = 0; b < this.indent_level; b++) a.push(this.indent_string), this.line_char_count += this.indent_string.length
                    }, this.print_token = function(a) {
                        (a || "" !== a) && this.output.length && "\n" === this.output[this.output.length - 1] && (this.print_indentation(this.output), a = b(a)), this.print_token_raw(a)
                    }, this.print_token_raw = function(a) {
                        a && "" !== a && (a.length > 1 && "\n" === a[a.length - 1] ? (this.output.push(a.slice(0, -1)), this.print_newline(!1, this.output)) : this.output.push(a));
                        for (var b = 0; b < this.newlines; b++) this.print_newline(b > 0, this.output);
                        this.newlines = 0
                    }, this.indent = function() {
                        this.indent_level++
                    }, this.unindent = function() {
                        this.indent_level > 0 && this.indent_level--
                    }
                }, this
            }
            var h, i, j, k, l, m, n, o, p, q;
            for (d = d || {}, void 0 !== d.wrap_line_length && 0 !== parseInt(d.wrap_line_length, 10) || void 0 === d.max_char || 0 === parseInt(d.max_char, 10) || (d.wrap_line_length = d.max_char), i = d.indent_inner_html || !0, j = parseInt(d.indent_size || 1, 10), k = d.indent_char || "	", m = d.brace_style || "collapse", l = 0 === parseInt(d.wrap_line_length, 10) ? 32786 : parseInt(d.wrap_line_length || 1e5, 10), n = d.unformatted || ["a", "span", "bdo", "em", "strong", "dfn", "code", "samp", "kbd", "var", "cite", "abbr", "acronym", "q", "sub", "sup", "tt", "i", "b", "big", "small", "u", "s", "strike", "font", "ins", "del", "pre", "address", "dt", "h1", "h2", "h3", "h4", "h5", "h6"], o = d.preserve_newlines || !0, p = o ? parseInt(d.max_preserve_newlines || 32786, 10) : 0, q = d.indent_handlebars || !0, h = new g, h.printer(c, k, j, l, m);;) {
                var r = h.get_token();
                if (h.token_text = r[0], h.token_type = r[1], "TK_EOF" === h.token_type) break;
                switch (h.token_type) {
                    case "TK_TAG_START":
                        h.print_newline(!1, h.output), h.print_token(h.token_text), h.indent_content && (h.indent(), h.indent_content = !1), h.current_mode = "CONTENT";
                        break;
                    case "TK_TAG_STYLE":
                    case "TK_TAG_SCRIPT":
                        h.print_newline(!1, h.output), h.print_token(h.token_text), h.current_mode = "CONTENT";
                        break;
                    case "TK_TAG_END":
                        if ("TK_CONTENT" === h.last_token && "" === h.last_text) {
                            var s = h.token_text.match(/\w+/)[0],
                                t = null;
                            h.output.length && (t = h.output[h.output.length - 1].match(/(?:<|{{#)\s*(\w+)/)), (null === t || t[1] !== s) && h.print_newline(!1, h.output)
                        }
                        h.print_token(h.token_text), h.current_mode = "CONTENT";
                        break;
                    case "TK_TAG_SINGLE":
                        var u = h.token_text.match(/^\s*<([a-z]+)/i);
                        u && h.Utils.in_array(u[1], n) || h.print_newline(!1, h.output), h.print_token(h.token_text), h.current_mode = "CONTENT";
                        break;
                    case "TK_TAG_HANDLEBARS_ELSE":
                        h.print_token(h.token_text), h.indent_content && (h.indent(), h.indent_content = !1), h.current_mode = "CONTENT";
                        break;
                    case "TK_CONTENT":
                        h.print_token(h.token_text), h.current_mode = "TAG";
                        break;
                    case "TK_STYLE":
                    case "TK_SCRIPT":
                        if ("" !== h.token_text) {
                            h.print_newline(!1, h.output);
                            var v, w = h.token_text,
                                x = 1;
                            "TK_SCRIPT" === h.token_type ? v = "function" == typeof e && e : "TK_STYLE" === h.token_type && (v = "function" == typeof f && f), "keep" === d.indent_scripts ? x = 0 : "separate" === d.indent_scripts && (x = -h.indent_level);
                            var y = h.get_full_indent(x);
                            if (v) w = v(w.replace(/^\s*/, y), d);
                            else {
                                var z = w.match(/^\s*/)[0],
                                    A = z.match(/[^\n\r]*$/)[0].split(h.indent_string).length - 1,
                                    B = h.get_full_indent(x - A);
                                w = w.replace(/^\s*/, y).replace(/\r\n|\r|\n/g, "\n" + B).replace(/\s+$/, "")
                            }
                            w && (h.print_token_raw(y + a(w)), h.print_newline(!1, h.output))
                        }
                        h.current_mode = "TAG"
                }
                h.last_token = h.token_type, h.last_text = h.token_text
            }
            return h.output.join("")
        }
        if ("function" == typeof define && define.amd) define(["./beautify", "./beautify-css"], function(a, b) {
            return {
                html_beautify: function(d, e) {
                    return c(d, e, a, b)
                }
            }
        });
        else if ("undefined" != typeof exports) {
            var d = require("./beautify.js").js_beautify,
                e = require("./beautify-css.js").css_beautify;
            exports.html_beautify = function(a, b) {
                return c(a, b, d, e)
            }
        } else "undefined" != typeof window ? window.html_beautify = function(a, b) {
            return c(a, b, window.js_beautify, window.css_beautify)
        } : "undefined" != typeof global && (global.html_beautify = function(a, b) {
            return c(a, b, global.js_beautify, global.css_beautify)
        })
    }();