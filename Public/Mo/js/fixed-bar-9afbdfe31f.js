(function e(t, n, r) {
    function s(o, u) {
        if (!n[o]) {
            if (!t[o]) {
                var a = typeof require == "function" && require;
                if (!u && a) return a(o, !0);
                if (i) return i(o, !0);
                var f = new Error("Cannot find module '" + o + "'");
                throw f.code = "MODULE_NOT_FOUND", f
            }
            var l = n[o] = {exports: {}};
            t[o][0].call(l.exports, function (e) {
                var n = t[o][1][e];
                return s(n ? n : e)
            }, l, l.exports, e, t, n, r)
        }
        return n[o].exports
    }

    var i = typeof require == "function" && require;
    for (var o = 0; o < r.length; o++) s(r[o]);
    return s
})({
    1: [function (require, module, exports) {
        "use strict";
        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor)
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor
            }
        }();
        var _tab = require("../../ui/tab");
        var _tab2 = _interopRequireDefault(_tab);

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {"default": obj}
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function")
            }
        }

        var FixedBar = function () {
            function FixedBar() {
                _classCallCheck(this, FixedBar)
            }

            _createClass(FixedBar, [{
                key: "initQrcode", value: function initQrcode() {
                    var hideTimer = void 0;
                    var showTimer = void 0;
                    $(".fixed-content .code").hover(function () {
                        if (hideTimer) {
                            hideTimer = clearTimeout(hideTimer);
                            hideTimer = null
                        }
                        showTimer = setTimeout(function () {
                            $(".fixed-content .code-layer").show()
                        }, 100)
                    }, function () {
                        if (showTimer) {
                            showTimer = clearTimeout(showTimer);
                            showTimer = null
                        }
                        hideTimer = setTimeout(function () {
                            $(".fixed-content .code-layer").hide()
                        }, 100)
                    });
                    var tab = new _tab2["default"]({
                        tabs: ".code-layer .tab-item",
                        contents: ".code-layer .tab-content"
                    });
                    tab.init()
                }
            }, {
                key: "initFeedback", value: function initFeedback() {
                    var hideTimer = void 0;
                    var showTimer = void 0;
                    $(".fixed-content .feedback").hover(function () {
                        if (hideTimer) {
                            hideTimer = clearTimeout(hideTimer);
                            hideTimer = null
                        }
                        showTimer = setTimeout(function () {
                            $(".fixed-content .layer").show()
                        }, 100)
                    }, function () {
                        if (showTimer) {
                            showTimer = clearTimeout(showTimer);
                            showTimer = null
                        }
                        hideTimer = setTimeout(function () {
                            $(".fixed-content .layer").hide()
                        }, 100)
                    })
                }
            }, {
                key: "initQQConsult", value: function initQQConsult() {
                    var reFetchTimer = -1;
                    $(".fixed-content .qq").off("click").on("click", function (e) {
                        if (window.BizQQWPA) {
                            return
                        }
                        if (reFetchTimer !== -1) {
                            return
                        }
                        reFetchTimer = setTimeout(function () {
                            reFetchTimer = -1;
                            if (!window.BizQQWPA) {
                                $.getScript(location.protocol + "//" + location.host + "/Httpspxy/index?url=" + encodeURIComponent("http://wpa.b.qq.com/cgi/wpa.php"), function () {
                                })
                            }
                        }, 500)
                    });
                    $.getScript(location.protocol + "//" + location.host + "/Httpspxy/index?url=" + encodeURIComponent("http://wpa.b.qq.com/cgi/wpa.php"), function () {
                        var $qqElm = $(".fixed-content .qq");
                        var qq = $qqElm.attr("_wpa_plat_qq");
                        var qId = "wpaId" + 1e4;
                        $qqElm.attr("id", qId);
                        if (qq && qq.match(/^(400|800)\d{6,7}$/)) {
                            BizQQWPA.addCustom({aty: "0", nameAccount: qq, selector: qId})
                        }
                    })
                }
            }, {
                key: "init", value: function init(params) {
                    var arr = params && params.length > 0 ? params : ["Qrcode", "Feedback", "QQConsult"];
                    for (var i = 0; i < arr.length; i++) {
                        this["init" + arr[i]]()
                    }
                }
            }]);
            return FixedBar
        }();
        var fixedBar = new FixedBar;
        fixedBar.init();

    }, {"../../ui/tab": 2}], 2: [function (require, module, exports) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {value: true});
        var _createClass = function () {
            function defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor)
                }
            }

            return function (Constructor, protoProps, staticProps) {
                if (protoProps) defineProperties(Constructor.prototype, protoProps);
                if (staticProps) defineProperties(Constructor, staticProps);
                return Constructor
            }
        }();

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function")
            }
        }

        var Tab = function () {
            function Tab(options) {
                _classCallCheck(this, Tab);
                this.tabs = $(options.tabs);
                this.contents = $(options.contents);
                this.curIndex = options.curIndex || 0;
                this.curClass = options.curClass || "cur"
            }

            _createClass(Tab, [{
                key: "tab", value: function tab(index) {
                    this.tabs.removeClass(this.curClass);
                    this.tabs.eq(index).addClass(this.curClass);
                    this.contents.hide().eq(index).show()
                }
            }, {
                key: "init", value: function init() {
                    var self = this;
                    this.contents.hide();
                    this.tabs.each(function (index, ele) {
                        $(ele).on("click", function (event) {
                            event.stopPropagation();
                            self.tab(index)
                        })
                    });
                    this.tab(this.curIndex)
                }
            }]);
            return Tab
        }();
        exports["default"] = Tab;
        window._cfUI = window._cfUI || {};
        window._cfUI.tab = Tab;

    }, {}]
}, {}, [1]);
/*  |xGv00|182dba8df03f983137b2db50d094f6b5 */