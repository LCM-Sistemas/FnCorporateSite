(function (a, b) {
    if (typeof mejs === 'undefined') {
        console.error('MediaElement.js library is not loaded.');
        return;
    }

    void 0 === mejs.plugins &&
        ((mejs.plugins = {}),
        (mejs.plugins.silverlight = []),
        mejs.plugins.silverlight.push({ types: [] })),
        (mejs.HtmlMediaElementShim = mejs.HtmlMediaElementShim || {
            getTypeFromFile: mejs.Utils.getTypeFromFile,
        }),
        void 0 === mejs.MediaFeatures && (mejs.MediaFeatures = mejs.Features),
        void 0 === mejs.Utility && (mejs.Utility = mejs.Utils);
    var c = MediaElementPlayer.prototype.init;
    MediaElementPlayer.prototype.init = function () {
        (this.options.classPrefix = "mejs-"),
            (this.$media = this.$node = b(this.node)),
            c.call(this);
    };
    var d = MediaElementPlayer.prototype._meReady;
    (MediaElementPlayer.prototype._meReady = function () {
        (this.container = b(this.container)),
            (this.controls = b(this.controls)),
            (this.layers = b(this.layers)),
            d.apply(this, arguments);
    }),
        (MediaElementPlayer.prototype.getElement = function (a) {
            return void 0 !== b && a instanceof b ? a[0] : a;
        }),
        (MediaElementPlayer.prototype.buildfeatures = function (a, c, d, e) {
            for (
                var f = [
                    "playpause",
                    "current",
                    "progress",
                    "duration",
                    "tracks",
                    "volume",
                    "fullscreen",
                ],
                g = 0,
                h = this.options.features.length;
                g < h;
                g++
            ) {
                var i = this.options.features[g];
                if (this["build" + i])
                    try {
                        f.indexOf(i) === -1
                            ? this["build" + i](a, b(c), b(d), e)
                            : this["build" + i](a, c, d, e);
                    } catch (j) {
                        console.error("error building " + i, j);
                    }
            }
        });
})(window, jQuery);