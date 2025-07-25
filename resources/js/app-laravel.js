/******/ (function(modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/ 	var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/ 	function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/ 		if(installedModules[moduleId]) {
            /******/ 			return installedModules[moduleId].exports;
            /******/ 		}
        /******/ 		// Create a new module (and put it into the cache)
        /******/ 		var module = installedModules[moduleId] = {
            /******/ 			i: moduleId,
            /******/ 			l: false,
            /******/ 			exports: {}
            /******/ 		};
        /******/
        /******/ 		// Execute the module function
        /******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/ 		module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/ 		return module.exports;
        /******/ 	}
    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/ 	__webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/ 	__webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/ 	__webpack_require__.d = function(exports, name, getter) {
        /******/ 		if(!__webpack_require__.o(exports, name)) {
            /******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
            /******/ 		}
        /******/ 	};
    /******/
    /******/ 	// define __esModule on exports
    /******/ 	__webpack_require__.r = function(exports) {
        /******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
            /******/ 		}
        /******/ 		Object.defineProperty(exports, '__esModule', { value: true });
        /******/ 	};
    /******/
    /******/ 	// create a fake namespace object
    /******/ 	// mode & 1: value is a module id, require it
    /******/ 	// mode & 2: merge all properties of value into the ns
    /******/ 	// mode & 4: return value when already ns object
    /******/ 	// mode & 8|1: behave like require
    /******/ 	__webpack_require__.t = function(value, mode) {
        /******/ 		if(mode & 1) value = __webpack_require__(value);
        /******/ 		if(mode & 8) return value;
        /******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/ 		var ns = Object.create(null);
        /******/ 		__webpack_require__.r(ns);
        /******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
        /******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
        /******/ 		return ns;
        /******/ 	};
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/ 	__webpack_require__.n = function(module) {
        /******/ 		var getter = module && module.__esModule ?
            /******/ 			function getDefault() { return module['default']; } :
            /******/ 			function getModuleExports() { return module; };
        /******/ 		__webpack_require__.d(getter, 'a', getter);
        /******/ 		return getter;
        /******/ 	};
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
    /******/
    /******/ 	// __webpack_public_path__
    /******/ 	__webpack_require__.p = "/";
    /******/
    /******/
    /******/ 	// Load entry module and return exports
    /******/ 	return __webpack_require__(__webpack_require__.s = 1);
    /******/ })
/************************************************************************/
/******/ ({

    /***/ "./node_modules/after/index.js":
    /*!*************************************!*\
  !*** ./node_modules/after/index.js ***!
  \*************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        module.exports = after

        function after(count, callback, err_cb) {
            var bail = false
            err_cb = err_cb || noop
            proxy.count = count

            return (count === 0) ? callback() : proxy

            function proxy(err, result) {
                if (proxy.count <= 0) {
                    throw new Error('after called too many times')
                }
                --proxy.count

                // after first error, rest are passed to err_cb
                if (err) {
                    bail = true
                    callback(err)
                    // future error callbacks will go to error handler
                    callback = err_cb
                } else if (proxy.count === 0 && !bail) {
                    callback(null, result)
                }
            }
        }

        function noop() {}


        /***/ }),

    /***/ "./node_modules/arraybuffer.slice/index.js":
    /*!*************************************************!*\
  !*** ./node_modules/arraybuffer.slice/index.js ***!
  \*************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * An abstraction for slicing an arraybuffer even when
         * ArrayBuffer.prototype.slice is not supported
         *
         * @api public
         */

        module.exports = function(arraybuffer, start, end) {
            var bytes = arraybuffer.byteLength;
            start = start || 0;
            end = end || bytes;

            if (arraybuffer.slice) { return arraybuffer.slice(start, end); }

            if (start < 0) { start += bytes; }
            if (end < 0) { end += bytes; }
            if (end > bytes) { end = bytes; }

            if (start >= bytes || start >= end || bytes === 0) {
                return new ArrayBuffer(0);
            }

            var abv = new Uint8Array(arraybuffer);
            var result = new Uint8Array(end - start);
            for (var i = start, ii = 0; i < end; i++, ii++) {
                result[ii] = abv[i];
            }
            return result.buffer;
        };


        /***/ }),

    /***/ "./node_modules/backo2/index.js":
    /*!**************************************!*\
  !*** ./node_modules/backo2/index.js ***!
  \**************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        /**
         * Expose `Backoff`.
         */

        module.exports = Backoff;

        /**
         * Initialize backoff timer with `opts`.
         *
         * - `min` initial timeout in milliseconds [100]
         * - `max` max timeout [10000]
         * - `jitter` [0]
         * - `factor` [2]
         *
         * @param {Object} opts
         * @api public
         */

        function Backoff(opts) {
            opts = opts || {};
            this.ms = opts.min || 100;
            this.max = opts.max || 10000;
            this.factor = opts.factor || 2;
            this.jitter = opts.jitter > 0 && opts.jitter <= 1 ? opts.jitter : 0;
            this.attempts = 0;
        }

        /**
         * Return the backoff duration.
         *
         * @return {Number}
         * @api public
         */

        Backoff.prototype.duration = function(){
            var ms = this.ms * Math.pow(this.factor, this.attempts++);
            if (this.jitter) {
                var rand =  Math.random();
                var deviation = Math.floor(rand * this.jitter * ms);
                ms = (Math.floor(rand * 10) & 1) == 0  ? ms - deviation : ms + deviation;
            }
            return Math.min(ms, this.max) | 0;
        };

        /**
         * Reset the number of attempts.
         *
         * @api public
         */

        Backoff.prototype.reset = function(){
            this.attempts = 0;
        };

        /**
         * Set the minimum duration
         *
         * @api public
         */

        Backoff.prototype.setMin = function(min){
            this.ms = min;
        };

        /**
         * Set the maximum duration
         *
         * @api public
         */

        Backoff.prototype.setMax = function(max){
            this.max = max;
        };

        /**
         * Set the jitter
         *
         * @api public
         */

        Backoff.prototype.setJitter = function(jitter){
            this.jitter = jitter;
        };



        /***/ }),

    /***/ "./node_modules/base64-js/index.js":
    /*!*****************************************!*\
  !*** ./node_modules/base64-js/index.js ***!
  \*****************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        exports.byteLength = byteLength
        exports.toByteArray = toByteArray
        exports.fromByteArray = fromByteArray

        var lookup = []
        var revLookup = []
        var Arr = typeof Uint8Array !== 'undefined' ? Uint8Array : Array

        var code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
        for (var i = 0, len = code.length; i < len; ++i) {
            lookup[i] = code[i]
            revLookup[code.charCodeAt(i)] = i
        }

// Support decoding URL-safe base64 strings, as Node.js does.
// See: https://en.wikipedia.org/wiki/Base64#URL_applications
        revLookup['-'.charCodeAt(0)] = 62
        revLookup['_'.charCodeAt(0)] = 63

        function getLens (b64) {
            var len = b64.length

            if (len % 4 > 0) {
                throw new Error('Invalid string. Length must be a multiple of 4')
            }

            // Trim off extra bytes after placeholder bytes are found
            // See: https://github.com/beatgammit/base64-js/issues/42
            var validLen = b64.indexOf('=')
            if (validLen === -1) validLen = len

            var placeHoldersLen = validLen === len
                ? 0
                : 4 - (validLen % 4)

            return [validLen, placeHoldersLen]
        }

// base64 is 4/3 + up to two characters of the original data
        function byteLength (b64) {
            var lens = getLens(b64)
            var validLen = lens[0]
            var placeHoldersLen = lens[1]
            return ((validLen + placeHoldersLen) * 3 / 4) - placeHoldersLen
        }

        function _byteLength (b64, validLen, placeHoldersLen) {
            return ((validLen + placeHoldersLen) * 3 / 4) - placeHoldersLen
        }

        function toByteArray (b64) {
            var tmp
            var lens = getLens(b64)
            var validLen = lens[0]
            var placeHoldersLen = lens[1]

            var arr = new Arr(_byteLength(b64, validLen, placeHoldersLen))

            var curByte = 0

            // if there are placeholders, only get up to the last complete 4 chars
            var len = placeHoldersLen > 0
                ? validLen - 4
                : validLen

            var i
            for (i = 0; i < len; i += 4) {
                tmp =
                    (revLookup[b64.charCodeAt(i)] << 18) |
                    (revLookup[b64.charCodeAt(i + 1)] << 12) |
                    (revLookup[b64.charCodeAt(i + 2)] << 6) |
                    revLookup[b64.charCodeAt(i + 3)]
                arr[curByte++] = (tmp >> 16) & 0xFF
                arr[curByte++] = (tmp >> 8) & 0xFF
                arr[curByte++] = tmp & 0xFF
            }

            if (placeHoldersLen === 2) {
                tmp =
                    (revLookup[b64.charCodeAt(i)] << 2) |
                    (revLookup[b64.charCodeAt(i + 1)] >> 4)
                arr[curByte++] = tmp & 0xFF
            }

            if (placeHoldersLen === 1) {
                tmp =
                    (revLookup[b64.charCodeAt(i)] << 10) |
                    (revLookup[b64.charCodeAt(i + 1)] << 4) |
                    (revLookup[b64.charCodeAt(i + 2)] >> 2)
                arr[curByte++] = (tmp >> 8) & 0xFF
                arr[curByte++] = tmp & 0xFF
            }

            return arr
        }

        function tripletToBase64 (num) {
            return lookup[num >> 18 & 0x3F] +
                lookup[num >> 12 & 0x3F] +
                lookup[num >> 6 & 0x3F] +
                lookup[num & 0x3F]
        }

        function encodeChunk (uint8, start, end) {
            var tmp
            var output = []
            for (var i = start; i < end; i += 3) {
                tmp =
                    ((uint8[i] << 16) & 0xFF0000) +
                    ((uint8[i + 1] << 8) & 0xFF00) +
                    (uint8[i + 2] & 0xFF)
                output.push(tripletToBase64(tmp))
            }
            return output.join('')
        }

        function fromByteArray (uint8) {
            var tmp
            var len = uint8.length
            var extraBytes = len % 3 // if we have 1 byte left, pad 2 bytes
            var parts = []
            var maxChunkLength = 16383 // must be multiple of 3

            // go through the array every three bytes, we'll deal with trailing stuff later
            for (var i = 0, len2 = len - extraBytes; i < len2; i += maxChunkLength) {
                parts.push(encodeChunk(
                    uint8, i, (i + maxChunkLength) > len2 ? len2 : (i + maxChunkLength)
                ))
            }

            // pad the end with zeros, but make sure to not forget the extra bytes
            if (extraBytes === 1) {
                tmp = uint8[len - 1]
                parts.push(
                    lookup[tmp >> 2] +
                    lookup[(tmp << 4) & 0x3F] +
                    '=='
                )
            } else if (extraBytes === 2) {
                tmp = (uint8[len - 2] << 8) + uint8[len - 1]
                parts.push(
                    lookup[tmp >> 10] +
                    lookup[(tmp >> 4) & 0x3F] +
                    lookup[(tmp << 2) & 0x3F] +
                    '='
                )
            }

            return parts.join('')
        }


        /***/ }),

    /***/ "./node_modules/blob/index.js":
    /*!************************************!*\
  !*** ./node_modules/blob/index.js ***!
  \************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Create a blob builder even when vendor prefixes exist
         */

        var BlobBuilder = typeof BlobBuilder !== 'undefined' ? BlobBuilder :
            typeof WebKitBlobBuilder !== 'undefined' ? WebKitBlobBuilder :
                typeof MSBlobBuilder !== 'undefined' ? MSBlobBuilder :
                    typeof MozBlobBuilder !== 'undefined' ? MozBlobBuilder :
                        false;

        /**
         * Check if Blob constructor is supported
         */

        var blobSupported = (function() {
            try {
                var a = new Blob(['hi']);
                return a.size === 2;
            } catch(e) {
                return false;
            }
        })();

        /**
         * Check if Blob constructor supports ArrayBufferViews
         * Fails in Safari 6, so we need to map to ArrayBuffers there.
         */

        var blobSupportsArrayBufferView = blobSupported && (function() {
            try {
                var b = new Blob([new Uint8Array([1,2])]);
                return b.size === 2;
            } catch(e) {
                return false;
            }
        })();

        /**
         * Check if BlobBuilder is supported
         */

        var blobBuilderSupported = BlobBuilder
            && BlobBuilder.prototype.append
            && BlobBuilder.prototype.getBlob;

        /**
         * Helper function that maps ArrayBufferViews to ArrayBuffers
         * Used by BlobBuilder constructor and old browsers that didn't
         * support it in the Blob constructor.
         */

        function mapArrayBufferViews(ary) {
            return ary.map(function(chunk) {
                if (chunk.buffer instanceof ArrayBuffer) {
                    var buf = chunk.buffer;

                    // if this is a subarray, make a copy so we only
                    // include the subarray region from the underlying buffer
                    if (chunk.byteLength !== buf.byteLength) {
                        var copy = new Uint8Array(chunk.byteLength);
                        copy.set(new Uint8Array(buf, chunk.byteOffset, chunk.byteLength));
                        buf = copy.buffer;
                    }

                    return buf;
                }

                return chunk;
            });
        }

        function BlobBuilderConstructor(ary, options) {
            options = options || {};

            var bb = new BlobBuilder();
            mapArrayBufferViews(ary).forEach(function(part) {
                bb.append(part);
            });

            return (options.type) ? bb.getBlob(options.type) : bb.getBlob();
        };

        function BlobConstructor(ary, options) {
            return new Blob(mapArrayBufferViews(ary), options || {});
        };

        if (typeof Blob !== 'undefined') {
            BlobBuilderConstructor.prototype = Blob.prototype;
            BlobConstructor.prototype = Blob.prototype;
        }

        module.exports = (function() {
            if (blobSupported) {
                return blobSupportsArrayBufferView ? Blob : BlobConstructor;
            } else if (blobBuilderSupported) {
                return BlobBuilderConstructor;
            } else {
                return undefined;
            }
        })();


        /***/ }),

    /***/ "./node_modules/buffer/index.js":
    /*!**************************************!*\
  !*** ./node_modules/buffer/index.js ***!
  \**************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";
        /* WEBPACK VAR INJECTION */(function(global) {/*!
 * The buffer module from node.js, for the browser.
 *
 * @author   Feross Aboukhadijeh <http://feross.org>
 * @license  MIT
 */
            /* eslint-disable no-proto */



            var base64 = __webpack_require__(/*! base64-js */ "./node_modules/base64-js/index.js")
            var ieee754 = __webpack_require__(/*! ieee754 */ "./node_modules/ieee754/index.js")
            var isArray = __webpack_require__(/*! isarray */ "./node_modules/isarray/index.js")

            exports.Buffer = Buffer
            exports.SlowBuffer = SlowBuffer
            exports.INSPECT_MAX_BYTES = 50

            /**
             * If `Buffer.TYPED_ARRAY_SUPPORT`:
             *   === true    Use Uint8Array implementation (fastest)
             *   === false   Use Object implementation (most compatible, even IE6)
             *
             * Browsers that support typed arrays are IE 10+, Firefox 4+, Chrome 7+, Safari 5.1+,
             * Opera 11.6+, iOS 4.2+.
             *
             * Due to various browser bugs, sometimes the Object implementation will be used even
             * when the browser supports typed arrays.
             *
             * Note:
             *
             *   - Firefox 4-29 lacks support for adding new properties to `Uint8Array` instances,
             *     See: https://bugzilla.mozilla.org/show_bug.cgi?id=695438.
             *
             *   - Chrome 9-10 is missing the `TypedArray.prototype.subarray` function.
             *
             *   - IE10 has a broken `TypedArray.prototype.subarray` function which returns arrays of
             *     incorrect length in some situations.

             * We detect these buggy browsers and set `Buffer.TYPED_ARRAY_SUPPORT` to `false` so they
             * get the Object implementation, which is slower but behaves correctly.
             */
            Buffer.TYPED_ARRAY_SUPPORT = global.TYPED_ARRAY_SUPPORT !== undefined
                ? global.TYPED_ARRAY_SUPPORT
                : typedArraySupport()

            /*
 * Export kMaxLength after typed array support is determined.
 */
            exports.kMaxLength = kMaxLength()

            function typedArraySupport () {
                try {
                    var arr = new Uint8Array(1)
                    arr.__proto__ = {__proto__: Uint8Array.prototype, foo: function () { return 42 }}
                    return arr.foo() === 42 && // typed array instances can be augmented
                        typeof arr.subarray === 'function' && // chrome 9-10 lack `subarray`
                        arr.subarray(1, 1).byteLength === 0 // ie10 has broken `subarray`
                } catch (e) {
                    return false
                }
            }

            function kMaxLength () {
                return Buffer.TYPED_ARRAY_SUPPORT
                    ? 0x7fffffff
                    : 0x3fffffff
            }

            function createBuffer (that, length) {
                if (kMaxLength() < length) {
                    throw new RangeError('Invalid typed array length')
                }
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    // Return an augmented `Uint8Array` instance, for best performance
                    that = new Uint8Array(length)
                    that.__proto__ = Buffer.prototype
                } else {
                    // Fallback: Return an object instance of the Buffer class
                    if (that === null) {
                        that = new Buffer(length)
                    }
                    that.length = length
                }

                return that
            }

            /**
             * The Buffer constructor returns instances of `Uint8Array` that have their
             * prototype changed to `Buffer.prototype`. Furthermore, `Buffer` is a subclass of
             * `Uint8Array`, so the returned instances will have all the node `Buffer` methods
             * and the `Uint8Array` methods. Square bracket notation works as expected -- it
             * returns a single octet.
             *
             * The `Uint8Array` prototype remains unmodified.
             */

            function Buffer (arg, encodingOrOffset, length) {
                if (!Buffer.TYPED_ARRAY_SUPPORT && !(this instanceof Buffer)) {
                    return new Buffer(arg, encodingOrOffset, length)
                }

                // Common case.
                if (typeof arg === 'number') {
                    if (typeof encodingOrOffset === 'string') {
                        throw new Error(
                            'If encoding is specified then the first argument must be a string'
                        )
                    }
                    return allocUnsafe(this, arg)
                }
                return from(this, arg, encodingOrOffset, length)
            }

            Buffer.poolSize = 8192 // not used by this implementation

// TODO: Legacy, not needed anymore. Remove in next major version.
            Buffer._augment = function (arr) {
                arr.__proto__ = Buffer.prototype
                return arr
            }

            function from (that, value, encodingOrOffset, length) {
                if (typeof value === 'number') {
                    throw new TypeError('"value" argument must not be a number')
                }

                if (typeof ArrayBuffer !== 'undefined' && value instanceof ArrayBuffer) {
                    return fromArrayBuffer(that, value, encodingOrOffset, length)
                }

                if (typeof value === 'string') {
                    return fromString(that, value, encodingOrOffset)
                }

                return fromObject(that, value)
            }

            /**
             * Functionally equivalent to Buffer(arg, encoding) but throws a TypeError
             * if value is a number.
             * Buffer.from(str[, encoding])
             * Buffer.from(array)
             * Buffer.from(buffer)
             * Buffer.from(arrayBuffer[, byteOffset[, length]])
             **/
            Buffer.from = function (value, encodingOrOffset, length) {
                return from(null, value, encodingOrOffset, length)
            }

            if (Buffer.TYPED_ARRAY_SUPPORT) {
                Buffer.prototype.__proto__ = Uint8Array.prototype
                Buffer.__proto__ = Uint8Array
                if (typeof Symbol !== 'undefined' && Symbol.species &&
                    Buffer[Symbol.species] === Buffer) {
                    // Fix subarray() in ES2016. See: https://github.com/feross/buffer/pull/97
                    Object.defineProperty(Buffer, Symbol.species, {
                        value: null,
                        configurable: true
                    })
                }
            }

            function assertSize (size) {
                if (typeof size !== 'number') {
                    throw new TypeError('"size" argument must be a number')
                } else if (size < 0) {
                    throw new RangeError('"size" argument must not be negative')
                }
            }

            function alloc (that, size, fill, encoding) {
                assertSize(size)
                if (size <= 0) {
                    return createBuffer(that, size)
                }
                if (fill !== undefined) {
                    // Only pay attention to encoding if it's a string. This
                    // prevents accidentally sending in a number that would
                    // be interpretted as a start offset.
                    return typeof encoding === 'string'
                        ? createBuffer(that, size).fill(fill, encoding)
                        : createBuffer(that, size).fill(fill)
                }
                return createBuffer(that, size)
            }

            /**
             * Creates a new filled Buffer instance.
             * alloc(size[, fill[, encoding]])
             **/
            Buffer.alloc = function (size, fill, encoding) {
                return alloc(null, size, fill, encoding)
            }

            function allocUnsafe (that, size) {
                assertSize(size)
                that = createBuffer(that, size < 0 ? 0 : checked(size) | 0)
                if (!Buffer.TYPED_ARRAY_SUPPORT) {
                    for (var i = 0; i < size; ++i) {
                        that[i] = 0
                    }
                }
                return that
            }

            /**
             * Equivalent to Buffer(num), by default creates a non-zero-filled Buffer instance.
             * */
            Buffer.allocUnsafe = function (size) {
                return allocUnsafe(null, size)
            }
            /**
             * Equivalent to SlowBuffer(num), by default creates a non-zero-filled Buffer instance.
             */
            Buffer.allocUnsafeSlow = function (size) {
                return allocUnsafe(null, size)
            }

            function fromString (that, string, encoding) {
                if (typeof encoding !== 'string' || encoding === '') {
                    encoding = 'utf8'
                }

                if (!Buffer.isEncoding(encoding)) {
                    throw new TypeError('"encoding" must be a valid string encoding')
                }

                var length = byteLength(string, encoding) | 0
                that = createBuffer(that, length)

                var actual = that.write(string, encoding)

                if (actual !== length) {
                    // Writing a hex string, for example, that contains invalid characters will
                    // cause everything after the first invalid character to be ignored. (e.g.
                    // 'abxxcd' will be treated as 'ab')
                    that = that.slice(0, actual)
                }

                return that
            }

            function fromArrayLike (that, array) {
                var length = array.length < 0 ? 0 : checked(array.length) | 0
                that = createBuffer(that, length)
                for (var i = 0; i < length; i += 1) {
                    that[i] = array[i] & 255
                }
                return that
            }

            function fromArrayBuffer (that, array, byteOffset, length) {
                array.byteLength // this throws if `array` is not a valid ArrayBuffer

                if (byteOffset < 0 || array.byteLength < byteOffset) {
                    throw new RangeError('\'offset\' is out of bounds')
                }

                if (array.byteLength < byteOffset + (length || 0)) {
                    throw new RangeError('\'length\' is out of bounds')
                }

                if (byteOffset === undefined && length === undefined) {
                    array = new Uint8Array(array)
                } else if (length === undefined) {
                    array = new Uint8Array(array, byteOffset)
                } else {
                    array = new Uint8Array(array, byteOffset, length)
                }

                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    // Return an augmented `Uint8Array` instance, for best performance
                    that = array
                    that.__proto__ = Buffer.prototype
                } else {
                    // Fallback: Return an object instance of the Buffer class
                    that = fromArrayLike(that, array)
                }
                return that
            }

            function fromObject (that, obj) {
                if (Buffer.isBuffer(obj)) {
                    var len = checked(obj.length) | 0
                    that = createBuffer(that, len)

                    if (that.length === 0) {
                        return that
                    }

                    obj.copy(that, 0, 0, len)
                    return that
                }

                if (obj) {
                    if ((typeof ArrayBuffer !== 'undefined' &&
                        obj.buffer instanceof ArrayBuffer) || 'length' in obj) {
                        if (typeof obj.length !== 'number' || isnan(obj.length)) {
                            return createBuffer(that, 0)
                        }
                        return fromArrayLike(that, obj)
                    }

                    if (obj.type === 'Buffer' && isArray(obj.data)) {
                        return fromArrayLike(that, obj.data)
                    }
                }

                throw new TypeError('First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.')
            }

            function checked (length) {
                // Note: cannot use `length < kMaxLength()` here because that fails when
                // length is NaN (which is otherwise coerced to zero.)
                if (length >= kMaxLength()) {
                    throw new RangeError('Attempt to allocate Buffer larger than maximum ' +
                        'size: 0x' + kMaxLength().toString(16) + ' bytes')
                }
                return length | 0
            }

            function SlowBuffer (length) {
                if (+length != length) { // eslint-disable-line eqeqeq
                    length = 0
                }
                return Buffer.alloc(+length)
            }

            Buffer.isBuffer = function isBuffer (b) {
                return !!(b != null && b._isBuffer)
            }

            Buffer.compare = function compare (a, b) {
                if (!Buffer.isBuffer(a) || !Buffer.isBuffer(b)) {
                    throw new TypeError('Arguments must be Buffers')
                }

                if (a === b) return 0

                var x = a.length
                var y = b.length

                for (var i = 0, len = Math.min(x, y); i < len; ++i) {
                    if (a[i] !== b[i]) {
                        x = a[i]
                        y = b[i]
                        break
                    }
                }

                if (x < y) return -1
                if (y < x) return 1
                return 0
            }

            Buffer.isEncoding = function isEncoding (encoding) {
                switch (String(encoding).toLowerCase()) {
                    case 'hex':
                    case 'utf8':
                    case 'utf-8':
                    case 'ascii':
                    case 'latin1':
                    case 'binary':
                    case 'base64':
                    case 'ucs2':
                    case 'ucs-2':
                    case 'utf16le':
                    case 'utf-16le':
                        return true
                    default:
                        return false
                }
            }

            Buffer.concat = function concat (list, length) {
                if (!isArray(list)) {
                    throw new TypeError('"list" argument must be an Array of Buffers')
                }

                if (list.length === 0) {
                    return Buffer.alloc(0)
                }

                var i
                if (length === undefined) {
                    length = 0
                    for (i = 0; i < list.length; ++i) {
                        length += list[i].length
                    }
                }

                var buffer = Buffer.allocUnsafe(length)
                var pos = 0
                for (i = 0; i < list.length; ++i) {
                    var buf = list[i]
                    if (!Buffer.isBuffer(buf)) {
                        throw new TypeError('"list" argument must be an Array of Buffers')
                    }
                    buf.copy(buffer, pos)
                    pos += buf.length
                }
                return buffer
            }

            function byteLength (string, encoding) {
                if (Buffer.isBuffer(string)) {
                    return string.length
                }
                if (typeof ArrayBuffer !== 'undefined' && typeof ArrayBuffer.isView === 'function' &&
                    (ArrayBuffer.isView(string) || string instanceof ArrayBuffer)) {
                    return string.byteLength
                }
                if (typeof string !== 'string') {
                    string = '' + string
                }

                var len = string.length
                if (len === 0) return 0

                // Use a for loop to avoid recursion
                var loweredCase = false
                for (;;) {
                    switch (encoding) {
                        case 'ascii':
                        case 'latin1':
                        case 'binary':
                            return len
                        case 'utf8':
                        case 'utf-8':
                        case undefined:
                            return utf8ToBytes(string).length
                        case 'ucs2':
                        case 'ucs-2':
                        case 'utf16le':
                        case 'utf-16le':
                            return len * 2
                        case 'hex':
                            return len >>> 1
                        case 'base64':
                            return base64ToBytes(string).length
                        default:
                            if (loweredCase) return utf8ToBytes(string).length // assume utf8
                            encoding = ('' + encoding).toLowerCase()
                            loweredCase = true
                    }
                }
            }
            Buffer.byteLength = byteLength

            function slowToString (encoding, start, end) {
                var loweredCase = false

                // No need to verify that "this.length <= MAX_UINT32" since it's a read-only
                // property of a typed array.

                // This behaves neither like String nor Uint8Array in that we set start/end
                // to their upper/lower bounds if the value passed is out of range.
                // undefined is handled specially as per ECMA-262 6th Edition,
                // Section 13.3.3.7 Runtime Semantics: KeyedBindingInitialization.
                if (start === undefined || start < 0) {
                    start = 0
                }
                // Return early if start > this.length. Done here to prevent potential uint32
                // coercion fail below.
                if (start > this.length) {
                    return ''
                }

                if (end === undefined || end > this.length) {
                    end = this.length
                }

                if (end <= 0) {
                    return ''
                }

                // Force coersion to uint32. This will also coerce falsey/NaN values to 0.
                end >>>= 0
                start >>>= 0

                if (end <= start) {
                    return ''
                }

                if (!encoding) encoding = 'utf8'

                while (true) {
                    switch (encoding) {
                        case 'hex':
                            return hexSlice(this, start, end)

                        case 'utf8':
                        case 'utf-8':
                            return utf8Slice(this, start, end)

                        case 'ascii':
                            return asciiSlice(this, start, end)

                        case 'latin1':
                        case 'binary':
                            return latin1Slice(this, start, end)

                        case 'base64':
                            return base64Slice(this, start, end)

                        case 'ucs2':
                        case 'ucs-2':
                        case 'utf16le':
                        case 'utf-16le':
                            return utf16leSlice(this, start, end)

                        default:
                            if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
                            encoding = (encoding + '').toLowerCase()
                            loweredCase = true
                    }
                }
            }

// The property is used by `Buffer.isBuffer` and `is-buffer` (in Safari 5-7) to detect
// Buffer instances.
            Buffer.prototype._isBuffer = true

            function swap (b, n, m) {
                var i = b[n]
                b[n] = b[m]
                b[m] = i
            }

            Buffer.prototype.swap16 = function swap16 () {
                var len = this.length
                if (len % 2 !== 0) {
                    throw new RangeError('Buffer size must be a multiple of 16-bits')
                }
                for (var i = 0; i < len; i += 2) {
                    swap(this, i, i + 1)
                }
                return this
            }

            Buffer.prototype.swap32 = function swap32 () {
                var len = this.length
                if (len % 4 !== 0) {
                    throw new RangeError('Buffer size must be a multiple of 32-bits')
                }
                for (var i = 0; i < len; i += 4) {
                    swap(this, i, i + 3)
                    swap(this, i + 1, i + 2)
                }
                return this
            }

            Buffer.prototype.swap64 = function swap64 () {
                var len = this.length
                if (len % 8 !== 0) {
                    throw new RangeError('Buffer size must be a multiple of 64-bits')
                }
                for (var i = 0; i < len; i += 8) {
                    swap(this, i, i + 7)
                    swap(this, i + 1, i + 6)
                    swap(this, i + 2, i + 5)
                    swap(this, i + 3, i + 4)
                }
                return this
            }

            Buffer.prototype.toString = function toString () {
                var length = this.length | 0
                if (length === 0) return ''
                if (arguments.length === 0) return utf8Slice(this, 0, length)
                return slowToString.apply(this, arguments)
            }

            Buffer.prototype.equals = function equals (b) {
                if (!Buffer.isBuffer(b)) throw new TypeError('Argument must be a Buffer')
                if (this === b) return true
                return Buffer.compare(this, b) === 0
            }

            Buffer.prototype.inspect = function inspect () {
                var str = ''
                var max = exports.INSPECT_MAX_BYTES
                if (this.length > 0) {
                    str = this.toString('hex', 0, max).match(/.{2}/g).join(' ')
                    if (this.length > max) str += ' ... '
                }
                return '<Buffer ' + str + '>'
            }

            Buffer.prototype.compare = function compare (target, start, end, thisStart, thisEnd) {
                if (!Buffer.isBuffer(target)) {
                    throw new TypeError('Argument must be a Buffer')
                }

                if (start === undefined) {
                    start = 0
                }
                if (end === undefined) {
                    end = target ? target.length : 0
                }
                if (thisStart === undefined) {
                    thisStart = 0
                }
                if (thisEnd === undefined) {
                    thisEnd = this.length
                }

                if (start < 0 || end > target.length || thisStart < 0 || thisEnd > this.length) {
                    throw new RangeError('out of range index')
                }

                if (thisStart >= thisEnd && start >= end) {
                    return 0
                }
                if (thisStart >= thisEnd) {
                    return -1
                }
                if (start >= end) {
                    return 1
                }

                start >>>= 0
                end >>>= 0
                thisStart >>>= 0
                thisEnd >>>= 0

                if (this === target) return 0

                var x = thisEnd - thisStart
                var y = end - start
                var len = Math.min(x, y)

                var thisCopy = this.slice(thisStart, thisEnd)
                var targetCopy = target.slice(start, end)

                for (var i = 0; i < len; ++i) {
                    if (thisCopy[i] !== targetCopy[i]) {
                        x = thisCopy[i]
                        y = targetCopy[i]
                        break
                    }
                }

                if (x < y) return -1
                if (y < x) return 1
                return 0
            }

// Finds either the first index of `val` in `buffer` at offset >= `byteOffset`,
// OR the last index of `val` in `buffer` at offset <= `byteOffset`.
//
// Arguments:
// - buffer - a Buffer to search
// - val - a string, Buffer, or number
// - byteOffset - an index into `buffer`; will be clamped to an int32
// - encoding - an optional encoding, relevant is val is a string
// - dir - true for indexOf, false for lastIndexOf
            function bidirectionalIndexOf (buffer, val, byteOffset, encoding, dir) {
                // Empty buffer means no match
                if (buffer.length === 0) return -1

                // Normalize byteOffset
                if (typeof byteOffset === 'string') {
                    encoding = byteOffset
                    byteOffset = 0
                } else if (byteOffset > 0x7fffffff) {
                    byteOffset = 0x7fffffff
                } else if (byteOffset < -0x80000000) {
                    byteOffset = -0x80000000
                }
                byteOffset = +byteOffset  // Coerce to Number.
                if (isNaN(byteOffset)) {
                    // byteOffset: it it's undefined, null, NaN, "foo", etc, search whole buffer
                    byteOffset = dir ? 0 : (buffer.length - 1)
                }

                // Normalize byteOffset: negative offsets start from the end of the buffer
                if (byteOffset < 0) byteOffset = buffer.length + byteOffset
                if (byteOffset >= buffer.length) {
                    if (dir) return -1
                    else byteOffset = buffer.length - 1
                } else if (byteOffset < 0) {
                    if (dir) byteOffset = 0
                    else return -1
                }

                // Normalize val
                if (typeof val === 'string') {
                    val = Buffer.from(val, encoding)
                }

                // Finally, search either indexOf (if dir is true) or lastIndexOf
                if (Buffer.isBuffer(val)) {
                    // Special case: looking for empty string/buffer always fails
                    if (val.length === 0) {
                        return -1
                    }
                    return arrayIndexOf(buffer, val, byteOffset, encoding, dir)
                } else if (typeof val === 'number') {
                    val = val & 0xFF // Search for a byte value [0-255]
                    if (Buffer.TYPED_ARRAY_SUPPORT &&
                        typeof Uint8Array.prototype.indexOf === 'function') {
                        if (dir) {
                            return Uint8Array.prototype.indexOf.call(buffer, val, byteOffset)
                        } else {
                            return Uint8Array.prototype.lastIndexOf.call(buffer, val, byteOffset)
                        }
                    }
                    return arrayIndexOf(buffer, [ val ], byteOffset, encoding, dir)
                }

                throw new TypeError('val must be string, number or Buffer')
            }

            function arrayIndexOf (arr, val, byteOffset, encoding, dir) {
                var indexSize = 1
                var arrLength = arr.length
                var valLength = val.length

                if (encoding !== undefined) {
                    encoding = String(encoding).toLowerCase()
                    if (encoding === 'ucs2' || encoding === 'ucs-2' ||
                        encoding === 'utf16le' || encoding === 'utf-16le') {
                        if (arr.length < 2 || val.length < 2) {
                            return -1
                        }
                        indexSize = 2
                        arrLength /= 2
                        valLength /= 2
                        byteOffset /= 2
                    }
                }

                function read (buf, i) {
                    if (indexSize === 1) {
                        return buf[i]
                    } else {
                        return buf.readUInt16BE(i * indexSize)
                    }
                }

                var i
                if (dir) {
                    var foundIndex = -1
                    for (i = byteOffset; i < arrLength; i++) {
                        if (read(arr, i) === read(val, foundIndex === -1 ? 0 : i - foundIndex)) {
                            if (foundIndex === -1) foundIndex = i
                            if (i - foundIndex + 1 === valLength) return foundIndex * indexSize
                        } else {
                            if (foundIndex !== -1) i -= i - foundIndex
                            foundIndex = -1
                        }
                    }
                } else {
                    if (byteOffset + valLength > arrLength) byteOffset = arrLength - valLength
                    for (i = byteOffset; i >= 0; i--) {
                        var found = true
                        for (var j = 0; j < valLength; j++) {
                            if (read(arr, i + j) !== read(val, j)) {
                                found = false
                                break
                            }
                        }
                        if (found) return i
                    }
                }

                return -1
            }

            Buffer.prototype.includes = function includes (val, byteOffset, encoding) {
                return this.indexOf(val, byteOffset, encoding) !== -1
            }

            Buffer.prototype.indexOf = function indexOf (val, byteOffset, encoding) {
                return bidirectionalIndexOf(this, val, byteOffset, encoding, true)
            }

            Buffer.prototype.lastIndexOf = function lastIndexOf (val, byteOffset, encoding) {
                return bidirectionalIndexOf(this, val, byteOffset, encoding, false)
            }

            function hexWrite (buf, string, offset, length) {
                offset = Number(offset) || 0
                var remaining = buf.length - offset
                if (!length) {
                    length = remaining
                } else {
                    length = Number(length)
                    if (length > remaining) {
                        length = remaining
                    }
                }

                // must be an even number of digits
                var strLen = string.length
                if (strLen % 2 !== 0) throw new TypeError('Invalid hex string')

                if (length > strLen / 2) {
                    length = strLen / 2
                }
                for (var i = 0; i < length; ++i) {
                    var parsed = parseInt(string.substr(i * 2, 2), 16)
                    if (isNaN(parsed)) return i
                    buf[offset + i] = parsed
                }
                return i
            }

            function utf8Write (buf, string, offset, length) {
                return blitBuffer(utf8ToBytes(string, buf.length - offset), buf, offset, length)
            }

            function asciiWrite (buf, string, offset, length) {
                return blitBuffer(asciiToBytes(string), buf, offset, length)
            }

            function latin1Write (buf, string, offset, length) {
                return asciiWrite(buf, string, offset, length)
            }

            function base64Write (buf, string, offset, length) {
                return blitBuffer(base64ToBytes(string), buf, offset, length)
            }

            function ucs2Write (buf, string, offset, length) {
                return blitBuffer(utf16leToBytes(string, buf.length - offset), buf, offset, length)
            }

            Buffer.prototype.write = function write (string, offset, length, encoding) {
                // Buffer#write(string)
                if (offset === undefined) {
                    encoding = 'utf8'
                    length = this.length
                    offset = 0
                    // Buffer#write(string, encoding)
                } else if (length === undefined && typeof offset === 'string') {
                    encoding = offset
                    length = this.length
                    offset = 0
                    // Buffer#write(string, offset[, length][, encoding])
                } else if (isFinite(offset)) {
                    offset = offset | 0
                    if (isFinite(length)) {
                        length = length | 0
                        if (encoding === undefined) encoding = 'utf8'
                    } else {
                        encoding = length
                        length = undefined
                    }
                    // legacy write(string, encoding, offset, length) - remove in v0.13
                } else {
                    throw new Error(
                        'Buffer.write(string, encoding, offset[, length]) is no longer supported'
                    )
                }

                var remaining = this.length - offset
                if (length === undefined || length > remaining) length = remaining

                if ((string.length > 0 && (length < 0 || offset < 0)) || offset > this.length) {
                    throw new RangeError('Attempt to write outside buffer bounds')
                }

                if (!encoding) encoding = 'utf8'

                var loweredCase = false
                for (;;) {
                    switch (encoding) {
                        case 'hex':
                            return hexWrite(this, string, offset, length)

                        case 'utf8':
                        case 'utf-8':
                            return utf8Write(this, string, offset, length)

                        case 'ascii':
                            return asciiWrite(this, string, offset, length)

                        case 'latin1':
                        case 'binary':
                            return latin1Write(this, string, offset, length)

                        case 'base64':
                            // Warning: maxLength not taken into account in base64Write
                            return base64Write(this, string, offset, length)

                        case 'ucs2':
                        case 'ucs-2':
                        case 'utf16le':
                        case 'utf-16le':
                            return ucs2Write(this, string, offset, length)

                        default:
                            if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
                            encoding = ('' + encoding).toLowerCase()
                            loweredCase = true
                    }
                }
            }

            Buffer.prototype.toJSON = function toJSON () {
                return {
                    type: 'Buffer',
                    data: Array.prototype.slice.call(this._arr || this, 0)
                }
            }

            function base64Slice (buf, start, end) {
                if (start === 0 && end === buf.length) {
                    return base64.fromByteArray(buf)
                } else {
                    return base64.fromByteArray(buf.slice(start, end))
                }
            }

            function utf8Slice (buf, start, end) {
                end = Math.min(buf.length, end)
                var res = []

                var i = start
                while (i < end) {
                    var firstByte = buf[i]
                    var codePoint = null
                    var bytesPerSequence = (firstByte > 0xEF) ? 4
                        : (firstByte > 0xDF) ? 3
                            : (firstByte > 0xBF) ? 2
                                : 1

                    if (i + bytesPerSequence <= end) {
                        var secondByte, thirdByte, fourthByte, tempCodePoint

                        switch (bytesPerSequence) {
                            case 1:
                                if (firstByte < 0x80) {
                                    codePoint = firstByte
                                }
                                break
                            case 2:
                                secondByte = buf[i + 1]
                                if ((secondByte & 0xC0) === 0x80) {
                                    tempCodePoint = (firstByte & 0x1F) << 0x6 | (secondByte & 0x3F)
                                    if (tempCodePoint > 0x7F) {
                                        codePoint = tempCodePoint
                                    }
                                }
                                break
                            case 3:
                                secondByte = buf[i + 1]
                                thirdByte = buf[i + 2]
                                if ((secondByte & 0xC0) === 0x80 && (thirdByte & 0xC0) === 0x80) {
                                    tempCodePoint = (firstByte & 0xF) << 0xC | (secondByte & 0x3F) << 0x6 | (thirdByte & 0x3F)
                                    if (tempCodePoint > 0x7FF && (tempCodePoint < 0xD800 || tempCodePoint > 0xDFFF)) {
                                        codePoint = tempCodePoint
                                    }
                                }
                                break
                            case 4:
                                secondByte = buf[i + 1]
                                thirdByte = buf[i + 2]
                                fourthByte = buf[i + 3]
                                if ((secondByte & 0xC0) === 0x80 && (thirdByte & 0xC0) === 0x80 && (fourthByte & 0xC0) === 0x80) {
                                    tempCodePoint = (firstByte & 0xF) << 0x12 | (secondByte & 0x3F) << 0xC | (thirdByte & 0x3F) << 0x6 | (fourthByte & 0x3F)
                                    if (tempCodePoint > 0xFFFF && tempCodePoint < 0x110000) {
                                        codePoint = tempCodePoint
                                    }
                                }
                        }
                    }

                    if (codePoint === null) {
                        // we did not generate a valid codePoint so insert a
                        // replacement char (U+FFFD) and advance only 1 byte
                        codePoint = 0xFFFD
                        bytesPerSequence = 1
                    } else if (codePoint > 0xFFFF) {
                        // encode to utf16 (surrogate pair dance)
                        codePoint -= 0x10000
                        res.push(codePoint >>> 10 & 0x3FF | 0xD800)
                        codePoint = 0xDC00 | codePoint & 0x3FF
                    }

                    res.push(codePoint)
                    i += bytesPerSequence
                }

                return decodeCodePointsArray(res)
            }

// Based on http://stackoverflow.com/a/22747272/680742, the browser with
// the lowest limit is Chrome, with 0x10000 args.
// We go 1 magnitude less, for safety
            var MAX_ARGUMENTS_LENGTH = 0x1000

            function decodeCodePointsArray (codePoints) {
                var len = codePoints.length
                if (len <= MAX_ARGUMENTS_LENGTH) {
                    return String.fromCharCode.apply(String, codePoints) // avoid extra slice()
                }

                // Decode in chunks to avoid "call stack size exceeded".
                var res = ''
                var i = 0
                while (i < len) {
                    res += String.fromCharCode.apply(
                        String,
                        codePoints.slice(i, i += MAX_ARGUMENTS_LENGTH)
                    )
                }
                return res
            }

            function asciiSlice (buf, start, end) {
                var ret = ''
                end = Math.min(buf.length, end)

                for (var i = start; i < end; ++i) {
                    ret += String.fromCharCode(buf[i] & 0x7F)
                }
                return ret
            }

            function latin1Slice (buf, start, end) {
                var ret = ''
                end = Math.min(buf.length, end)

                for (var i = start; i < end; ++i) {
                    ret += String.fromCharCode(buf[i])
                }
                return ret
            }

            function hexSlice (buf, start, end) {
                var len = buf.length

                if (!start || start < 0) start = 0
                if (!end || end < 0 || end > len) end = len

                var out = ''
                for (var i = start; i < end; ++i) {
                    out += toHex(buf[i])
                }
                return out
            }

            function utf16leSlice (buf, start, end) {
                var bytes = buf.slice(start, end)
                var res = ''
                for (var i = 0; i < bytes.length; i += 2) {
                    res += String.fromCharCode(bytes[i] + bytes[i + 1] * 256)
                }
                return res
            }

            Buffer.prototype.slice = function slice (start, end) {
                var len = this.length
                start = ~~start
                end = end === undefined ? len : ~~end

                if (start < 0) {
                    start += len
                    if (start < 0) start = 0
                } else if (start > len) {
                    start = len
                }

                if (end < 0) {
                    end += len
                    if (end < 0) end = 0
                } else if (end > len) {
                    end = len
                }

                if (end < start) end = start

                var newBuf
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    newBuf = this.subarray(start, end)
                    newBuf.__proto__ = Buffer.prototype
                } else {
                    var sliceLen = end - start
                    newBuf = new Buffer(sliceLen, undefined)
                    for (var i = 0; i < sliceLen; ++i) {
                        newBuf[i] = this[i + start]
                    }
                }

                return newBuf
            }

            /*
 * Need to make sure that buffer isn't trying to write out of bounds.
 */
            function checkOffset (offset, ext, length) {
                if ((offset % 1) !== 0 || offset < 0) throw new RangeError('offset is not uint')
                if (offset + ext > length) throw new RangeError('Trying to access beyond buffer length')
            }

            Buffer.prototype.readUIntLE = function readUIntLE (offset, byteLength, noAssert) {
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) checkOffset(offset, byteLength, this.length)

                var val = this[offset]
                var mul = 1
                var i = 0
                while (++i < byteLength && (mul *= 0x100)) {
                    val += this[offset + i] * mul
                }

                return val
            }

            Buffer.prototype.readUIntBE = function readUIntBE (offset, byteLength, noAssert) {
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) {
                    checkOffset(offset, byteLength, this.length)
                }

                var val = this[offset + --byteLength]
                var mul = 1
                while (byteLength > 0 && (mul *= 0x100)) {
                    val += this[offset + --byteLength] * mul
                }

                return val
            }

            Buffer.prototype.readUInt8 = function readUInt8 (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 1, this.length)
                return this[offset]
            }

            Buffer.prototype.readUInt16LE = function readUInt16LE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 2, this.length)
                return this[offset] | (this[offset + 1] << 8)
            }

            Buffer.prototype.readUInt16BE = function readUInt16BE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 2, this.length)
                return (this[offset] << 8) | this[offset + 1]
            }

            Buffer.prototype.readUInt32LE = function readUInt32LE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)

                return ((this[offset]) |
                    (this[offset + 1] << 8) |
                    (this[offset + 2] << 16)) +
                    (this[offset + 3] * 0x1000000)
            }

            Buffer.prototype.readUInt32BE = function readUInt32BE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)

                return (this[offset] * 0x1000000) +
                    ((this[offset + 1] << 16) |
                        (this[offset + 2] << 8) |
                        this[offset + 3])
            }

            Buffer.prototype.readIntLE = function readIntLE (offset, byteLength, noAssert) {
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) checkOffset(offset, byteLength, this.length)

                var val = this[offset]
                var mul = 1
                var i = 0
                while (++i < byteLength && (mul *= 0x100)) {
                    val += this[offset + i] * mul
                }
                mul *= 0x80

                if (val >= mul) val -= Math.pow(2, 8 * byteLength)

                return val
            }

            Buffer.prototype.readIntBE = function readIntBE (offset, byteLength, noAssert) {
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) checkOffset(offset, byteLength, this.length)

                var i = byteLength
                var mul = 1
                var val = this[offset + --i]
                while (i > 0 && (mul *= 0x100)) {
                    val += this[offset + --i] * mul
                }
                mul *= 0x80

                if (val >= mul) val -= Math.pow(2, 8 * byteLength)

                return val
            }

            Buffer.prototype.readInt8 = function readInt8 (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 1, this.length)
                if (!(this[offset] & 0x80)) return (this[offset])
                return ((0xff - this[offset] + 1) * -1)
            }

            Buffer.prototype.readInt16LE = function readInt16LE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 2, this.length)
                var val = this[offset] | (this[offset + 1] << 8)
                return (val & 0x8000) ? val | 0xFFFF0000 : val
            }

            Buffer.prototype.readInt16BE = function readInt16BE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 2, this.length)
                var val = this[offset + 1] | (this[offset] << 8)
                return (val & 0x8000) ? val | 0xFFFF0000 : val
            }

            Buffer.prototype.readInt32LE = function readInt32LE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)

                return (this[offset]) |
                    (this[offset + 1] << 8) |
                    (this[offset + 2] << 16) |
                    (this[offset + 3] << 24)
            }

            Buffer.prototype.readInt32BE = function readInt32BE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)

                return (this[offset] << 24) |
                    (this[offset + 1] << 16) |
                    (this[offset + 2] << 8) |
                    (this[offset + 3])
            }

            Buffer.prototype.readFloatLE = function readFloatLE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)
                return ieee754.read(this, offset, true, 23, 4)
            }

            Buffer.prototype.readFloatBE = function readFloatBE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 4, this.length)
                return ieee754.read(this, offset, false, 23, 4)
            }

            Buffer.prototype.readDoubleLE = function readDoubleLE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 8, this.length)
                return ieee754.read(this, offset, true, 52, 8)
            }

            Buffer.prototype.readDoubleBE = function readDoubleBE (offset, noAssert) {
                if (!noAssert) checkOffset(offset, 8, this.length)
                return ieee754.read(this, offset, false, 52, 8)
            }

            function checkInt (buf, value, offset, ext, max, min) {
                if (!Buffer.isBuffer(buf)) throw new TypeError('"buffer" argument must be a Buffer instance')
                if (value > max || value < min) throw new RangeError('"value" argument is out of bounds')
                if (offset + ext > buf.length) throw new RangeError('Index out of range')
            }

            Buffer.prototype.writeUIntLE = function writeUIntLE (value, offset, byteLength, noAssert) {
                value = +value
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) {
                    var maxBytes = Math.pow(2, 8 * byteLength) - 1
                    checkInt(this, value, offset, byteLength, maxBytes, 0)
                }

                var mul = 1
                var i = 0
                this[offset] = value & 0xFF
                while (++i < byteLength && (mul *= 0x100)) {
                    this[offset + i] = (value / mul) & 0xFF
                }

                return offset + byteLength
            }

            Buffer.prototype.writeUIntBE = function writeUIntBE (value, offset, byteLength, noAssert) {
                value = +value
                offset = offset | 0
                byteLength = byteLength | 0
                if (!noAssert) {
                    var maxBytes = Math.pow(2, 8 * byteLength) - 1
                    checkInt(this, value, offset, byteLength, maxBytes, 0)
                }

                var i = byteLength - 1
                var mul = 1
                this[offset + i] = value & 0xFF
                while (--i >= 0 && (mul *= 0x100)) {
                    this[offset + i] = (value / mul) & 0xFF
                }

                return offset + byteLength
            }

            Buffer.prototype.writeUInt8 = function writeUInt8 (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 1, 0xff, 0)
                if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
                this[offset] = (value & 0xff)
                return offset + 1
            }

            function objectWriteUInt16 (buf, value, offset, littleEndian) {
                if (value < 0) value = 0xffff + value + 1
                for (var i = 0, j = Math.min(buf.length - offset, 2); i < j; ++i) {
                    buf[offset + i] = (value & (0xff << (8 * (littleEndian ? i : 1 - i)))) >>>
                        (littleEndian ? i : 1 - i) * 8
                }
            }

            Buffer.prototype.writeUInt16LE = function writeUInt16LE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value & 0xff)
                    this[offset + 1] = (value >>> 8)
                } else {
                    objectWriteUInt16(this, value, offset, true)
                }
                return offset + 2
            }

            Buffer.prototype.writeUInt16BE = function writeUInt16BE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value >>> 8)
                    this[offset + 1] = (value & 0xff)
                } else {
                    objectWriteUInt16(this, value, offset, false)
                }
                return offset + 2
            }

            function objectWriteUInt32 (buf, value, offset, littleEndian) {
                if (value < 0) value = 0xffffffff + value + 1
                for (var i = 0, j = Math.min(buf.length - offset, 4); i < j; ++i) {
                    buf[offset + i] = (value >>> (littleEndian ? i : 3 - i) * 8) & 0xff
                }
            }

            Buffer.prototype.writeUInt32LE = function writeUInt32LE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset + 3] = (value >>> 24)
                    this[offset + 2] = (value >>> 16)
                    this[offset + 1] = (value >>> 8)
                    this[offset] = (value & 0xff)
                } else {
                    objectWriteUInt32(this, value, offset, true)
                }
                return offset + 4
            }

            Buffer.prototype.writeUInt32BE = function writeUInt32BE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value >>> 24)
                    this[offset + 1] = (value >>> 16)
                    this[offset + 2] = (value >>> 8)
                    this[offset + 3] = (value & 0xff)
                } else {
                    objectWriteUInt32(this, value, offset, false)
                }
                return offset + 4
            }

            Buffer.prototype.writeIntLE = function writeIntLE (value, offset, byteLength, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) {
                    var limit = Math.pow(2, 8 * byteLength - 1)

                    checkInt(this, value, offset, byteLength, limit - 1, -limit)
                }

                var i = 0
                var mul = 1
                var sub = 0
                this[offset] = value & 0xFF
                while (++i < byteLength && (mul *= 0x100)) {
                    if (value < 0 && sub === 0 && this[offset + i - 1] !== 0) {
                        sub = 1
                    }
                    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
                }

                return offset + byteLength
            }

            Buffer.prototype.writeIntBE = function writeIntBE (value, offset, byteLength, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) {
                    var limit = Math.pow(2, 8 * byteLength - 1)

                    checkInt(this, value, offset, byteLength, limit - 1, -limit)
                }

                var i = byteLength - 1
                var mul = 1
                var sub = 0
                this[offset + i] = value & 0xFF
                while (--i >= 0 && (mul *= 0x100)) {
                    if (value < 0 && sub === 0 && this[offset + i + 1] !== 0) {
                        sub = 1
                    }
                    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
                }

                return offset + byteLength
            }

            Buffer.prototype.writeInt8 = function writeInt8 (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 1, 0x7f, -0x80)
                if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
                if (value < 0) value = 0xff + value + 1
                this[offset] = (value & 0xff)
                return offset + 1
            }

            Buffer.prototype.writeInt16LE = function writeInt16LE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value & 0xff)
                    this[offset + 1] = (value >>> 8)
                } else {
                    objectWriteUInt16(this, value, offset, true)
                }
                return offset + 2
            }

            Buffer.prototype.writeInt16BE = function writeInt16BE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value >>> 8)
                    this[offset + 1] = (value & 0xff)
                } else {
                    objectWriteUInt16(this, value, offset, false)
                }
                return offset + 2
            }

            Buffer.prototype.writeInt32LE = function writeInt32LE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value & 0xff)
                    this[offset + 1] = (value >>> 8)
                    this[offset + 2] = (value >>> 16)
                    this[offset + 3] = (value >>> 24)
                } else {
                    objectWriteUInt32(this, value, offset, true)
                }
                return offset + 4
            }

            Buffer.prototype.writeInt32BE = function writeInt32BE (value, offset, noAssert) {
                value = +value
                offset = offset | 0
                if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
                if (value < 0) value = 0xffffffff + value + 1
                if (Buffer.TYPED_ARRAY_SUPPORT) {
                    this[offset] = (value >>> 24)
                    this[offset + 1] = (value >>> 16)
                    this[offset + 2] = (value >>> 8)
                    this[offset + 3] = (value & 0xff)
                } else {
                    objectWriteUInt32(this, value, offset, false)
                }
                return offset + 4
            }

            function checkIEEE754 (buf, value, offset, ext, max, min) {
                if (offset + ext > buf.length) throw new RangeError('Index out of range')
                if (offset < 0) throw new RangeError('Index out of range')
            }

            function writeFloat (buf, value, offset, littleEndian, noAssert) {
                if (!noAssert) {
                    checkIEEE754(buf, value, offset, 4, 3.4028234663852886e+38, -3.4028234663852886e+38)
                }
                ieee754.write(buf, value, offset, littleEndian, 23, 4)
                return offset + 4
            }

            Buffer.prototype.writeFloatLE = function writeFloatLE (value, offset, noAssert) {
                return writeFloat(this, value, offset, true, noAssert)
            }

            Buffer.prototype.writeFloatBE = function writeFloatBE (value, offset, noAssert) {
                return writeFloat(this, value, offset, false, noAssert)
            }

            function writeDouble (buf, value, offset, littleEndian, noAssert) {
                if (!noAssert) {
                    checkIEEE754(buf, value, offset, 8, 1.7976931348623157E+308, -1.7976931348623157E+308)
                }
                ieee754.write(buf, value, offset, littleEndian, 52, 8)
                return offset + 8
            }

            Buffer.prototype.writeDoubleLE = function writeDoubleLE (value, offset, noAssert) {
                return writeDouble(this, value, offset, true, noAssert)
            }

            Buffer.prototype.writeDoubleBE = function writeDoubleBE (value, offset, noAssert) {
                return writeDouble(this, value, offset, false, noAssert)
            }

// copy(targetBuffer, targetStart=0, sourceStart=0, sourceEnd=buffer.length)
            Buffer.prototype.copy = function copy (target, targetStart, start, end) {
                if (!start) start = 0
                if (!end && end !== 0) end = this.length
                if (targetStart >= target.length) targetStart = target.length
                if (!targetStart) targetStart = 0
                if (end > 0 && end < start) end = start

                // Copy 0 bytes; we're done
                if (end === start) return 0
                if (target.length === 0 || this.length === 0) return 0

                // Fatal error conditions
                if (targetStart < 0) {
                    throw new RangeError('targetStart out of bounds')
                }
                if (start < 0 || start >= this.length) throw new RangeError('sourceStart out of bounds')
                if (end < 0) throw new RangeError('sourceEnd out of bounds')

                // Are we oob?
                if (end > this.length) end = this.length
                if (target.length - targetStart < end - start) {
                    end = target.length - targetStart + start
                }

                var len = end - start
                var i

                if (this === target && start < targetStart && targetStart < end) {
                    // descending copy from end
                    for (i = len - 1; i >= 0; --i) {
                        target[i + targetStart] = this[i + start]
                    }
                } else if (len < 1000 || !Buffer.TYPED_ARRAY_SUPPORT) {
                    // ascending copy from start
                    for (i = 0; i < len; ++i) {
                        target[i + targetStart] = this[i + start]
                    }
                } else {
                    Uint8Array.prototype.set.call(
                        target,
                        this.subarray(start, start + len),
                        targetStart
                    )
                }

                return len
            }

// Usage:
//    buffer.fill(number[, offset[, end]])
//    buffer.fill(buffer[, offset[, end]])
//    buffer.fill(string[, offset[, end]][, encoding])
            Buffer.prototype.fill = function fill (val, start, end, encoding) {
                // Handle string cases:
                if (typeof val === 'string') {
                    if (typeof start === 'string') {
                        encoding = start
                        start = 0
                        end = this.length
                    } else if (typeof end === 'string') {
                        encoding = end
                        end = this.length
                    }
                    if (val.length === 1) {
                        var code = val.charCodeAt(0)
                        if (code < 256) {
                            val = code
                        }
                    }
                    if (encoding !== undefined && typeof encoding !== 'string') {
                        throw new TypeError('encoding must be a string')
                    }
                    if (typeof encoding === 'string' && !Buffer.isEncoding(encoding)) {
                        throw new TypeError('Unknown encoding: ' + encoding)
                    }
                } else if (typeof val === 'number') {
                    val = val & 255
                }

                // Invalid ranges are not set to a default, so can range check early.
                if (start < 0 || this.length < start || this.length < end) {
                    throw new RangeError('Out of range index')
                }

                if (end <= start) {
                    return this
                }

                start = start >>> 0
                end = end === undefined ? this.length : end >>> 0

                if (!val) val = 0

                var i
                if (typeof val === 'number') {
                    for (i = start; i < end; ++i) {
                        this[i] = val
                    }
                } else {
                    var bytes = Buffer.isBuffer(val)
                        ? val
                        : utf8ToBytes(new Buffer(val, encoding).toString())
                    var len = bytes.length
                    for (i = 0; i < end - start; ++i) {
                        this[i + start] = bytes[i % len]
                    }
                }

                return this
            }

// HELPER FUNCTIONS
// ================

            var INVALID_BASE64_RE = /[^+\/0-9A-Za-z-_]/g

            function base64clean (str) {
                // Node strips out invalid characters like \n and \t from the string, base64-js does not
                str = stringtrim(str).replace(INVALID_BASE64_RE, '')
                // Node converts strings with length < 2 to ''
                if (str.length < 2) return ''
                // Node allows for non-padded base64 strings (missing trailing ===), base64-js does not
                while (str.length % 4 !== 0) {
                    str = str + '='
                }
                return str
            }

            function stringtrim (str) {
                if (str.trim) return str.trim()
                return str.replace(/^\s+|\s+$/g, '')
            }

            function toHex (n) {
                if (n < 16) return '0' + n.toString(16)
                return n.toString(16)
            }

            function utf8ToBytes (string, units) {
                units = units || Infinity
                var codePoint
                var length = string.length
                var leadSurrogate = null
                var bytes = []

                for (var i = 0; i < length; ++i) {
                    codePoint = string.charCodeAt(i)

                    // is surrogate component
                    if (codePoint > 0xD7FF && codePoint < 0xE000) {
                        // last char was a lead
                        if (!leadSurrogate) {
                            // no lead yet
                            if (codePoint > 0xDBFF) {
                                // unexpected trail
                                if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
                                continue
                            } else if (i + 1 === length) {
                                // unpaired lead
                                if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
                                continue
                            }

                            // valid lead
                            leadSurrogate = codePoint

                            continue
                        }

                        // 2 leads in a row
                        if (codePoint < 0xDC00) {
                            if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
                            leadSurrogate = codePoint
                            continue
                        }

                        // valid surrogate pair
                        codePoint = (leadSurrogate - 0xD800 << 10 | codePoint - 0xDC00) + 0x10000
                    } else if (leadSurrogate) {
                        // valid bmp char, but last char was a lead
                        if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
                    }

                    leadSurrogate = null

                    // encode utf8
                    if (codePoint < 0x80) {
                        if ((units -= 1) < 0) break
                        bytes.push(codePoint)
                    } else if (codePoint < 0x800) {
                        if ((units -= 2) < 0) break
                        bytes.push(
                            codePoint >> 0x6 | 0xC0,
                            codePoint & 0x3F | 0x80
                        )
                    } else if (codePoint < 0x10000) {
                        if ((units -= 3) < 0) break
                        bytes.push(
                            codePoint >> 0xC | 0xE0,
                            codePoint >> 0x6 & 0x3F | 0x80,
                            codePoint & 0x3F | 0x80
                        )
                    } else if (codePoint < 0x110000) {
                        if ((units -= 4) < 0) break
                        bytes.push(
                            codePoint >> 0x12 | 0xF0,
                            codePoint >> 0xC & 0x3F | 0x80,
                            codePoint >> 0x6 & 0x3F | 0x80,
                            codePoint & 0x3F | 0x80
                        )
                    } else {
                        throw new Error('Invalid code point')
                    }
                }

                return bytes
            }

            function asciiToBytes (str) {
                var byteArray = []
                for (var i = 0; i < str.length; ++i) {
                    // Node's code seems to be doing this and not & 0x7F..
                    byteArray.push(str.charCodeAt(i) & 0xFF)
                }
                return byteArray
            }

            function utf16leToBytes (str, units) {
                var c, hi, lo
                var byteArray = []
                for (var i = 0; i < str.length; ++i) {
                    if ((units -= 2) < 0) break

                    c = str.charCodeAt(i)
                    hi = c >> 8
                    lo = c % 256
                    byteArray.push(lo)
                    byteArray.push(hi)
                }

                return byteArray
            }

            function base64ToBytes (str) {
                return base64.toByteArray(base64clean(str))
            }

            function blitBuffer (src, dst, offset, length) {
                for (var i = 0; i < length; ++i) {
                    if ((i + offset >= dst.length) || (i >= src.length)) break
                    dst[i + offset] = src[i]
                }
                return i
            }

            function isnan (val) {
                return val !== val // eslint-disable-line no-self-compare
            }

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

        /***/ }),

    /***/ "./node_modules/component-bind/index.js":
    /*!**********************************************!*\
  !*** ./node_modules/component-bind/index.js ***!
  \**********************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Slice reference.
         */

        var slice = [].slice;

        /**
         * Bind `obj` to `fn`.
         *
         * @param {Object} obj
         * @param {Function|String} fn or string
         * @return {Function}
         * @api public
         */

        module.exports = function(obj, fn){
            if ('string' == typeof fn) fn = obj[fn];
            if ('function' != typeof fn) throw new Error('bind() requires a function');
            var args = slice.call(arguments, 2);
            return function(){
                return fn.apply(obj, args.concat(slice.call(arguments)));
            }
        };


        /***/ }),

    /***/ "./node_modules/component-emitter/index.js":
    /*!*************************************************!*\
  !*** ./node_modules/component-emitter/index.js ***!
  \*************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Expose `Emitter`.
         */

        if (true) {
            module.exports = Emitter;
        }

        /**
         * Initialize a new `Emitter`.
         *
         * @api public
         */

        function Emitter(obj) {
            if (obj) return mixin(obj);
        };

        /**
         * Mixin the emitter properties.
         *
         * @param {Object} obj
         * @return {Object}
         * @api private
         */

        function mixin(obj) {
            for (var key in Emitter.prototype) {
                obj[key] = Emitter.prototype[key];
            }
            return obj;
        }

        /**
         * Listen on the given `event` with `fn`.
         *
         * @param {String} event
         * @param {Function} fn
         * @return {Emitter}
         * @api public
         */

        Emitter.prototype.on =
            Emitter.prototype.addEventListener = function(event, fn){
                this._callbacks = this._callbacks || {};
                (this._callbacks['$' + event] = this._callbacks['$' + event] || [])
                    .push(fn);
                return this;
            };

        /**
         * Adds an `event` listener that will be invoked a single
         * time then automatically removed.
         *
         * @param {String} event
         * @param {Function} fn
         * @return {Emitter}
         * @api public
         */

        Emitter.prototype.once = function(event, fn){
            function on() {
                this.off(event, on);
                fn.apply(this, arguments);
            }

            on.fn = fn;
            this.on(event, on);
            return this;
        };

        /**
         * Remove the given callback for `event` or all
         * registered callbacks.
         *
         * @param {String} event
         * @param {Function} fn
         * @return {Emitter}
         * @api public
         */

        Emitter.prototype.off =
            Emitter.prototype.removeListener =
                Emitter.prototype.removeAllListeners =
                    Emitter.prototype.removeEventListener = function(event, fn){
                        this._callbacks = this._callbacks || {};

                        // all
                        if (0 == arguments.length) {
                            this._callbacks = {};
                            return this;
                        }

                        // specific event
                        var callbacks = this._callbacks['$' + event];
                        if (!callbacks) return this;

                        // remove all handlers
                        if (1 == arguments.length) {
                            delete this._callbacks['$' + event];
                            return this;
                        }

                        // remove specific handler
                        var cb;
                        for (var i = 0; i < callbacks.length; i++) {
                            cb = callbacks[i];
                            if (cb === fn || cb.fn === fn) {
                                callbacks.splice(i, 1);
                                break;
                            }
                        }

                        // Remove event specific arrays for event types that no
                        // one is subscribed for to avoid memory leak.
                        if (callbacks.length === 0) {
                            delete this._callbacks['$' + event];
                        }

                        return this;
                    };

        /**
         * Emit `event` with the given args.
         *
         * @param {String} event
         * @param {Mixed} ...
         * @return {Emitter}
         */

        Emitter.prototype.emit = function(event){
            this._callbacks = this._callbacks || {};

            var args = new Array(arguments.length - 1)
                , callbacks = this._callbacks['$' + event];

            for (var i = 1; i < arguments.length; i++) {
                args[i - 1] = arguments[i];
            }

            if (callbacks) {
                callbacks = callbacks.slice(0);
                for (var i = 0, len = callbacks.length; i < len; ++i) {
                    callbacks[i].apply(this, args);
                }
            }

            return this;
        };

        /**
         * Return array of callbacks for `event`.
         *
         * @param {String} event
         * @return {Array}
         * @api public
         */

        Emitter.prototype.listeners = function(event){
            this._callbacks = this._callbacks || {};
            return this._callbacks['$' + event] || [];
        };

        /**
         * Check if this emitter has `event` handlers.
         *
         * @param {String} event
         * @return {Boolean}
         * @api public
         */

        Emitter.prototype.hasListeners = function(event){
            return !! this.listeners(event).length;
        };


        /***/ }),

    /***/ "./node_modules/component-inherit/index.js":
    /*!*************************************************!*\
  !*** ./node_modules/component-inherit/index.js ***!
  \*************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        module.exports = function(a, b){
            var fn = function(){};
            fn.prototype = b.prototype;
            a.prototype = new fn;
            a.prototype.constructor = a;
        };

        /***/ }),

    /***/ "./node_modules/debug/src/browser.js":
    /*!*******************************************!*\
  !*** ./node_modules/debug/src/browser.js ***!
  \*******************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /* WEBPACK VAR INJECTION */(function(process) {/**
         * This is the web browser implementation of `debug()`.
         *
         * Expose `debug()` as the module.
         */

        exports = module.exports = __webpack_require__(/*! ./debug */ "./node_modules/debug/src/debug.js");
            exports.log = log;
            exports.formatArgs = formatArgs;
            exports.save = save;
            exports.load = load;
            exports.useColors = useColors;
            exports.storage = 'undefined' != typeof chrome
            && 'undefined' != typeof chrome.storage
                ? chrome.storage.local
                : localstorage();

            /**
             * Colors.
             */

            exports.colors = [
                '#0000CC', '#0000FF', '#0033CC', '#0033FF', '#0066CC', '#0066FF', '#0099CC',
                '#0099FF', '#00CC00', '#00CC33', '#00CC66', '#00CC99', '#00CCCC', '#00CCFF',
                '#3300CC', '#3300FF', '#3333CC', '#3333FF', '#3366CC', '#3366FF', '#3399CC',
                '#3399FF', '#33CC00', '#33CC33', '#33CC66', '#33CC99', '#33CCCC', '#33CCFF',
                '#6600CC', '#6600FF', '#6633CC', '#6633FF', '#66CC00', '#66CC33', '#9900CC',
                '#9900FF', '#9933CC', '#9933FF', '#99CC00', '#99CC33', '#CC0000', '#CC0033',
                '#CC0066', '#CC0099', '#CC00CC', '#CC00FF', '#CC3300', '#CC3333', '#CC3366',
                '#CC3399', '#CC33CC', '#CC33FF', '#CC6600', '#CC6633', '#CC9900', '#CC9933',
                '#CCCC00', '#CCCC33', '#FF0000', '#FF0033', '#FF0066', '#FF0099', '#FF00CC',
                '#FF00FF', '#FF3300', '#FF3333', '#FF3366', '#FF3399', '#FF33CC', '#FF33FF',
                '#FF6600', '#FF6633', '#FF9900', '#FF9933', '#FFCC00', '#FFCC33'
            ];

            /**
             * Currently only WebKit-based Web Inspectors, Firefox >= v31,
             * and the Firebug extension (any Firefox version) are known
             * to support "%c" CSS customizations.
             *
             * TODO: add a `localStorage` variable to explicitly enable/disable colors
             */

            function useColors() {
                // NB: In an Electron preload script, document will be defined but not fully
                // initialized. Since we know we're in Chrome, we'll just detect this case
                // explicitly
                if (typeof window !== 'undefined' && window.process && window.process.type === 'renderer') {
                    return true;
                }

                // Internet Explorer and Edge do not support colors.
                if (typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/(edge|trident)\/(\d+)/)) {
                    return false;
                }

                // is webkit? http://stackoverflow.com/a/16459606/376773
                // document is undefined in react-native: https://github.com/facebook/react-native/pull/1632
                return (typeof document !== 'undefined' && document.documentElement && document.documentElement.style && document.documentElement.style.WebkitAppearance) ||
                    // is firebug? http://stackoverflow.com/a/398120/376773
                    (typeof window !== 'undefined' && window.console && (window.console.firebug || (window.console.exception && window.console.table))) ||
                    // is firefox >= v31?
                    // https://developer.mozilla.org/en-US/docs/Tools/Web_Console#Styling_messages
                    (typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/firefox\/(\d+)/) && parseInt(RegExp.$1, 10) >= 31) ||
                    // double check webkit in userAgent just in case we are in a worker
                    (typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/applewebkit\/(\d+)/));
            }

            /**
             * Map %j to `JSON.stringify()`, since no Web Inspectors do that by default.
             */

            exports.formatters.j = function(v) {
                try {
                    return JSON.stringify(v);
                } catch (err) {
                    return '[UnexpectedJSONParseError]: ' + err.message;
                }
            };


            /**
             * Colorize log arguments if enabled.
             *
             * @api public
             */

            function formatArgs(args) {
                var useColors = this.useColors;

                args[0] = (useColors ? '%c' : '')
                    + this.namespace
                    + (useColors ? ' %c' : ' ')
                    + args[0]
                    + (useColors ? '%c ' : ' ')
                    + '+' + exports.humanize(this.diff);

                if (!useColors) return;

                var c = 'color: ' + this.color;
                args.splice(1, 0, c, 'color: inherit')

                // the final "%c" is somewhat tricky, because there could be other
                // arguments passed either before or after the %c, so we need to
                // figure out the correct index to insert the CSS into
                var index = 0;
                var lastC = 0;
                args[0].replace(/%[a-zA-Z%]/g, function(match) {
                    if ('%%' === match) return;
                    index++;
                    if ('%c' === match) {
                        // we only are interested in the *last* %c
                        // (the user may have provided their own)
                        lastC = index;
                    }
                });

                args.splice(lastC, 0, c);
            }

            /**
             * Invokes `console.log()` when available.
             * No-op when `console.log` is not a "function".
             *
             * @api public
             */

            function log() {
                // this hackery is required for IE8/9, where
                // the `console.log` function doesn't have 'apply'
                return 'object' === typeof console
                    && console.log
                    && Function.prototype.apply.call(console.log, console, arguments);
            }

            /**
             * Save `namespaces`.
             *
             * @param {String} namespaces
             * @api private
             */

            function save(namespaces) {
                try {
                    if (null == namespaces) {
                        exports.storage.removeItem('debug');
                    } else {
                        exports.storage.debug = namespaces;
                    }
                } catch(e) {}
            }

            /**
             * Load `namespaces`.
             *
             * @return {String} returns the previously persisted debug modes
             * @api private
             */

            function load() {
                var r;
                try {
                    r = exports.storage.debug;
                } catch(e) {}

                // If debug isn't set in LS, and we're in Electron, try to load $DEBUG
                if (!r && typeof process !== 'undefined' && 'env' in process) {
                    r = process.env.DEBUG;
                }

                return r;
            }

            /**
             * Enable namespaces listed in `localStorage.debug` initially.
             */

            exports.enable(load());

            /**
             * Localstorage attempts to return the localstorage.
             *
             * This is necessary because safari throws
             * when a user disables cookies/localstorage
             * and you attempt to access it.
             *
             * @return {LocalStorage}
             * @api private
             */

            function localstorage() {
                try {
                    return window.localStorage;
                } catch (e) {}
            }

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../process/browser.js */ "./node_modules/process/browser.js")))

        /***/ }),

    /***/ "./node_modules/debug/src/debug.js":
    /*!*****************************************!*\
  !*** ./node_modules/debug/src/debug.js ***!
  \*****************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * This is the common logic for both the Node.js and web browser
         * implementations of `debug()`.
         *
         * Expose `debug()` as the module.
         */

        exports = module.exports = createDebug.debug = createDebug['default'] = createDebug;
        exports.coerce = coerce;
        exports.disable = disable;
        exports.enable = enable;
        exports.enabled = enabled;
        exports.humanize = __webpack_require__(/*! ms */ "./node_modules/ms/index.js");

        /**
         * Active `debug` instances.
         */
        exports.instances = [];

        /**
         * The currently active debug mode names, and names to skip.
         */

        exports.names = [];
        exports.skips = [];

        /**
         * Map of special "%n" handling functions, for the debug "format" argument.
         *
         * Valid key names are a single, lower or upper-case letter, i.e. "n" and "N".
         */

        exports.formatters = {};

        /**
         * Select a color.
         * @param {String} namespace
         * @return {Number}
         * @api private
         */

        function selectColor(namespace) {
            var hash = 0, i;

            for (i in namespace) {
                hash  = ((hash << 5) - hash) + namespace.charCodeAt(i);
                hash |= 0; // Convert to 32bit integer
            }

            return exports.colors[Math.abs(hash) % exports.colors.length];
        }

        /**
         * Create a debugger with the given `namespace`.
         *
         * @param {String} namespace
         * @return {Function}
         * @api public
         */

        function createDebug(namespace) {

            var prevTime;

            function debug() {
                // disabled?
                if (!debug.enabled) return;

                var self = debug;

                // set `diff` timestamp
                var curr = +new Date();
                var ms = curr - (prevTime || curr);
                self.diff = ms;
                self.prev = prevTime;
                self.curr = curr;
                prevTime = curr;

                // turn the `arguments` into a proper Array
                var args = new Array(arguments.length);
                for (var i = 0; i < args.length; i++) {
                    args[i] = arguments[i];
                }

                args[0] = exports.coerce(args[0]);

                if ('string' !== typeof args[0]) {
                    // anything else let's inspect with %O
                    args.unshift('%O');
                }

                // apply any `formatters` transformations
                var index = 0;
                args[0] = args[0].replace(/%([a-zA-Z%])/g, function(match, format) {
                    // if we encounter an escaped % then don't increase the array index
                    if (match === '%%') return match;
                    index++;
                    var formatter = exports.formatters[format];
                    if ('function' === typeof formatter) {
                        var val = args[index];
                        match = formatter.call(self, val);

                        // now we need to remove `args[index]` since it's inlined in the `format`
                        args.splice(index, 1);
                        index--;
                    }
                    return match;
                });

                // apply env-specific formatting (colors, etc.)
                exports.formatArgs.call(self, args);

                var logFn = debug.log || exports.log || console.log.bind(console);
                logFn.apply(self, args);
            }

            debug.namespace = namespace;
            debug.enabled = exports.enabled(namespace);
            debug.useColors = exports.useColors();
            debug.color = selectColor(namespace);
            debug.destroy = destroy;

            // env-specific initialization logic for debug instances
            if ('function' === typeof exports.init) {
                exports.init(debug);
            }

            exports.instances.push(debug);

            return debug;
        }

        function destroy () {
            var index = exports.instances.indexOf(this);
            if (index !== -1) {
                exports.instances.splice(index, 1);
                return true;
            } else {
                return false;
            }
        }

        /**
         * Enables a debug mode by namespaces. This can include modes
         * separated by a colon and wildcards.
         *
         * @param {String} namespaces
         * @api public
         */

        function enable(namespaces) {
            exports.save(namespaces);

            exports.names = [];
            exports.skips = [];

            var i;
            var split = (typeof namespaces === 'string' ? namespaces : '').split(/[\s,]+/);
            var len = split.length;

            for (i = 0; i < len; i++) {
                if (!split[i]) continue; // ignore empty strings
                namespaces = split[i].replace(/\*/g, '.*?');
                if (namespaces[0] === '-') {
                    exports.skips.push(new RegExp('^' + namespaces.substr(1) + '$'));
                } else {
                    exports.names.push(new RegExp('^' + namespaces + '$'));
                }
            }

            for (i = 0; i < exports.instances.length; i++) {
                var instance = exports.instances[i];
                instance.enabled = exports.enabled(instance.namespace);
            }
        }

        /**
         * Disable debug output.
         *
         * @api public
         */

        function disable() {
            exports.enable('');
        }

        /**
         * Returns true if the given mode name is enabled, false otherwise.
         *
         * @param {String} name
         * @return {Boolean}
         * @api public
         */

        function enabled(name) {
            if (name[name.length - 1] === '*') {
                return true;
            }
            var i, len;
            for (i = 0, len = exports.skips.length; i < len; i++) {
                if (exports.skips[i].test(name)) {
                    return false;
                }
            }
            for (i = 0, len = exports.names.length; i < len; i++) {
                if (exports.names[i].test(name)) {
                    return true;
                }
            }
            return false;
        }

        /**
         * Coerce `val`.
         *
         * @param {Mixed} val
         * @return {Mixed}
         * @api private
         */

        function coerce(val) {
            if (val instanceof Error) return val.stack || val.message;
            return val;
        }


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/globalThis.browser.js":
    /*!*****************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/globalThis.browser.js ***!
  \*****************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        module.exports = (function () {
            if (typeof self !== 'undefined') {
                return self;
            } else if (typeof window !== 'undefined') {
                return window;
            } else {
                return Function('return this')(); // eslint-disable-line no-new-func
            }
        })();


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/index.js":
    /*!****************************************************!*\
  !*** ./node_modules/engine.io-client/lib/index.js ***!
  \****************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        module.exports = __webpack_require__(/*! ./socket */ "./node_modules/engine.io-client/lib/socket.js");

        /**
         * Exports parser
         *
         * @api public
         *
         */
        module.exports.parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/socket.js":
    /*!*****************************************************!*\
  !*** ./node_modules/engine.io-client/lib/socket.js ***!
  \*****************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module dependencies.
         */

        var transports = __webpack_require__(/*! ./transports/index */ "./node_modules/engine.io-client/lib/transports/index.js");
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('engine.io-client:socket');
        var index = __webpack_require__(/*! indexof */ "./node_modules/indexof/index.js");
        var parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");
        var parseuri = __webpack_require__(/*! parseuri */ "./node_modules/engine.io-client/node_modules/parseuri/index.js");
        var parseqs = __webpack_require__(/*! parseqs */ "./node_modules/engine.io-client/node_modules/parseqs/index.js");

        /**
         * Module exports.
         */

        module.exports = Socket;

        /**
         * Socket constructor.
         *
         * @param {String|Object} uri or options
         * @param {Object} options
         * @api public
         */

        function Socket (uri, opts) {
            if (!(this instanceof Socket)) return new Socket(uri, opts);

            opts = opts || {};

            if (uri && 'object' === typeof uri) {
                opts = uri;
                uri = null;
            }

            if (uri) {
                uri = parseuri(uri);
                opts.hostname = uri.host;
                opts.secure = uri.protocol === 'https' || uri.protocol === 'wss';
                opts.port = uri.port;
                if (uri.query) opts.query = uri.query;
            } else if (opts.host) {
                opts.hostname = parseuri(opts.host).host;
            }

            this.secure = null != opts.secure ? opts.secure
                : (typeof location !== 'undefined' && 'https:' === location.protocol);

            if (opts.hostname && !opts.port) {
                // if no port is specified manually, use the protocol default
                opts.port = this.secure ? '443' : '80';
            }

            this.agent = opts.agent || false;
            this.hostname = opts.hostname ||
                (typeof location !== 'undefined' ? location.hostname : 'localhost');
            this.port = opts.port || (typeof location !== 'undefined' && location.port
                ? location.port
                : (this.secure ? 443 : 80));
            this.query = opts.query || {};
            if ('string' === typeof this.query) this.query = parseqs.decode(this.query);
            this.upgrade = false !== opts.upgrade;
            this.path = (opts.path || '/engine.io').replace(/\/$/, '') + '/';
            this.forceJSONP = !!opts.forceJSONP;
            this.jsonp = false !== opts.jsonp;
            this.forceBase64 = !!opts.forceBase64;
            this.enablesXDR = !!opts.enablesXDR;
            this.withCredentials = false !== opts.withCredentials;
            this.timestampParam = opts.timestampParam || 't';
            this.timestampRequests = opts.timestampRequests;
            this.transports = opts.transports || ['polling', 'websocket'];
            this.transportOptions = opts.transportOptions || {};
            this.readyState = '';
            this.writeBuffer = [];
            this.prevBufferLen = 0;
            this.policyPort = opts.policyPort || 843;
            this.rememberUpgrade = opts.rememberUpgrade || false;
            this.binaryType = null;
            this.onlyBinaryUpgrades = opts.onlyBinaryUpgrades;
            this.perMessageDeflate = false !== opts.perMessageDeflate ? (opts.perMessageDeflate || {}) : false;

            if (true === this.perMessageDeflate) this.perMessageDeflate = {};
            if (this.perMessageDeflate && null == this.perMessageDeflate.threshold) {
                this.perMessageDeflate.threshold = 1024;
            }

            // SSL options for Node.js client
            this.pfx = opts.pfx || null;
            this.key = opts.key || null;
            this.passphrase = opts.passphrase || null;
            this.cert = opts.cert || null;
            this.ca = opts.ca || null;
            this.ciphers = opts.ciphers || null;
            this.rejectUnauthorized = opts.rejectUnauthorized === undefined ? true : opts.rejectUnauthorized;
            this.forceNode = !!opts.forceNode;

            // detect ReactNative environment
            this.isReactNative = (typeof navigator !== 'undefined' && typeof navigator.product === 'string' && navigator.product.toLowerCase() === 'reactnative');

            // other options for Node.js or ReactNative client
            if (typeof self === 'undefined' || this.isReactNative) {
                if (opts.extraHeaders && Object.keys(opts.extraHeaders).length > 0) {
                    this.extraHeaders = opts.extraHeaders;
                }

                if (opts.localAddress) {
                    this.localAddress = opts.localAddress;
                }
            }

            // set on handshake
            this.id = null;
            this.upgrades = null;
            this.pingInterval = null;
            this.pingTimeout = null;

            // set on heartbeat
            this.pingIntervalTimer = null;
            this.pingTimeoutTimer = null;

            this.open();
        }

        Socket.priorWebsocketSuccess = false;

        /**
         * Mix in `Emitter`.
         */

        Emitter(Socket.prototype);

        /**
         * Protocol version.
         *
         * @api public
         */

        Socket.protocol = parser.protocol; // this is an int

        /**
         * Expose deps for legacy compatibility
         * and standalone browser access.
         */

        Socket.Socket = Socket;
        Socket.Transport = __webpack_require__(/*! ./transport */ "./node_modules/engine.io-client/lib/transport.js");
        Socket.transports = __webpack_require__(/*! ./transports/index */ "./node_modules/engine.io-client/lib/transports/index.js");
        Socket.parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");

        /**
         * Creates transport of the given type.
         *
         * @param {String} transport name
         * @return {Transport}
         * @api private
         */

        Socket.prototype.createTransport = function (name) {
            debug('creating transport "%s"', name);
            var query = clone(this.query);

            // append engine.io protocol identifier
            query.EIO = parser.protocol;

            // transport name
            query.transport = name;

            // per-transport options
            var options = this.transportOptions[name] || {};

            // session id if we already have one
            if (this.id) query.sid = this.id;

            var transport = new transports[name]({
                query: query,
                socket: this,
                agent: options.agent || this.agent,
                hostname: options.hostname || this.hostname,
                port: options.port || this.port,
                secure: options.secure || this.secure,
                path: options.path || this.path,
                forceJSONP: options.forceJSONP || this.forceJSONP,
                jsonp: options.jsonp || this.jsonp,
                forceBase64: options.forceBase64 || this.forceBase64,
                enablesXDR: options.enablesXDR || this.enablesXDR,
                withCredentials: options.withCredentials || this.withCredentials,
                timestampRequests: options.timestampRequests || this.timestampRequests,
                timestampParam: options.timestampParam || this.timestampParam,
                policyPort: options.policyPort || this.policyPort,
                pfx: options.pfx || this.pfx,
                key: options.key || this.key,
                passphrase: options.passphrase || this.passphrase,
                cert: options.cert || this.cert,
                ca: options.ca || this.ca,
                ciphers: options.ciphers || this.ciphers,
                rejectUnauthorized: options.rejectUnauthorized || this.rejectUnauthorized,
                perMessageDeflate: options.perMessageDeflate || this.perMessageDeflate,
                extraHeaders: options.extraHeaders || this.extraHeaders,
                forceNode: options.forceNode || this.forceNode,
                localAddress: options.localAddress || this.localAddress,
                requestTimeout: options.requestTimeout || this.requestTimeout,
                protocols: options.protocols || void (0),
                isReactNative: this.isReactNative
            });

            return transport;
        };

        function clone (obj) {
            var o = {};
            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    o[i] = obj[i];
                }
            }
            return o;
        }

        /**
         * Initializes transport to use and starts probe.
         *
         * @api private
         */
        Socket.prototype.open = function () {
            var transport;
            if (this.rememberUpgrade && Socket.priorWebsocketSuccess && this.transports.indexOf('websocket') !== -1) {
                transport = 'websocket';
            } else if (0 === this.transports.length) {
                // Emit error on next tick so it can be listened to
                var self = this;
                setTimeout(function () {
                    self.emit('error', 'No transports available');
                }, 0);
                return;
            } else {
                transport = this.transports[0];
            }
            this.readyState = 'opening';

            // Retry with the next transport if the transport is disabled (jsonp: false)
            try {
                transport = this.createTransport(transport);
            } catch (e) {
                this.transports.shift();
                this.open();
                return;
            }

            transport.open();
            this.setTransport(transport);
        };

        /**
         * Sets the current transport. Disables the existing one (if any).
         *
         * @api private
         */

        Socket.prototype.setTransport = function (transport) {
            debug('setting transport %s', transport.name);
            var self = this;

            if (this.transport) {
                debug('clearing existing transport %s', this.transport.name);
                this.transport.removeAllListeners();
            }

            // set up transport
            this.transport = transport;

            // set up transport listeners
            transport
                .on('drain', function () {
                    self.onDrain();
                })
                .on('packet', function (packet) {
                    self.onPacket(packet);
                })
                .on('error', function (e) {
                    self.onError(e);
                })
                .on('close', function () {
                    self.onClose('transport close');
                });
        };

        /**
         * Probes a transport.
         *
         * @param {String} transport name
         * @api private
         */

        Socket.prototype.probe = function (name) {
            debug('probing transport "%s"', name);
            var transport = this.createTransport(name, { probe: 1 });
            var failed = false;
            var self = this;

            Socket.priorWebsocketSuccess = false;

            function onTransportOpen () {
                if (self.onlyBinaryUpgrades) {
                    var upgradeLosesBinary = !this.supportsBinary && self.transport.supportsBinary;
                    failed = failed || upgradeLosesBinary;
                }
                if (failed) return;

                debug('probe transport "%s" opened', name);
                transport.send([{ type: 'ping', data: 'probe' }]);
                transport.once('packet', function (msg) {
                    if (failed) return;
                    if ('pong' === msg.type && 'probe' === msg.data) {
                        debug('probe transport "%s" pong', name);
                        self.upgrading = true;
                        self.emit('upgrading', transport);
                        if (!transport) return;
                        Socket.priorWebsocketSuccess = 'websocket' === transport.name;

                        debug('pausing current transport "%s"', self.transport.name);
                        self.transport.pause(function () {
                            if (failed) return;
                            if ('closed' === self.readyState) return;
                            debug('changing transport and sending upgrade packet');

                            cleanup();

                            self.setTransport(transport);
                            transport.send([{ type: 'upgrade' }]);
                            self.emit('upgrade', transport);
                            transport = null;
                            self.upgrading = false;
                            self.flush();
                        });
                    } else {
                        debug('probe transport "%s" failed', name);
                        var err = new Error('probe error');
                        err.transport = transport.name;
                        self.emit('upgradeError', err);
                    }
                });
            }

            function freezeTransport () {
                if (failed) return;

                // Any callback called by transport should be ignored since now
                failed = true;

                cleanup();

                transport.close();
                transport = null;
            }

            // Handle any error that happens while probing
            function onerror (err) {
                var error = new Error('probe error: ' + err);
                error.transport = transport.name;

                freezeTransport();

                debug('probe transport "%s" failed because of error: %s', name, err);

                self.emit('upgradeError', error);
            }

            function onTransportClose () {
                onerror('transport closed');
            }

            // When the socket is closed while we're probing
            function onclose () {
                onerror('socket closed');
            }

            // When the socket is upgraded while we're probing
            function onupgrade (to) {
                if (transport && to.name !== transport.name) {
                    debug('"%s" works - aborting "%s"', to.name, transport.name);
                    freezeTransport();
                }
            }

            // Remove all listeners on the transport and on self
            function cleanup () {
                transport.removeListener('open', onTransportOpen);
                transport.removeListener('error', onerror);
                transport.removeListener('close', onTransportClose);
                self.removeListener('close', onclose);
                self.removeListener('upgrading', onupgrade);
            }

            transport.once('open', onTransportOpen);
            transport.once('error', onerror);
            transport.once('close', onTransportClose);

            this.once('close', onclose);
            this.once('upgrading', onupgrade);

            transport.open();
        };

        /**
         * Called when connection is deemed open.
         *
         * @api public
         */

        Socket.prototype.onOpen = function () {
            debug('socket open');
            this.readyState = 'open';
            Socket.priorWebsocketSuccess = 'websocket' === this.transport.name;
            this.emit('open');
            this.flush();

            // we check for `readyState` in case an `open`
            // listener already closed the socket
            if ('open' === this.readyState && this.upgrade && this.transport.pause) {
                debug('starting upgrade probes');
                for (var i = 0, l = this.upgrades.length; i < l; i++) {
                    this.probe(this.upgrades[i]);
                }
            }
        };

        /**
         * Handles a packet.
         *
         * @api private
         */

        Socket.prototype.onPacket = function (packet) {
            if ('opening' === this.readyState || 'open' === this.readyState ||
                'closing' === this.readyState) {
                debug('socket receive: type "%s", data "%s"', packet.type, packet.data);

                this.emit('packet', packet);

                // Socket is live - any packet counts
                this.emit('heartbeat');

                switch (packet.type) {
                    case 'open':
                        this.onHandshake(JSON.parse(packet.data));
                        break;

                    case 'pong':
                        this.setPing();
                        this.emit('pong');
                        break;

                    case 'error':
                        var err = new Error('server error');
                        err.code = packet.data;
                        this.onError(err);
                        break;

                    case 'message':
                        this.emit('data', packet.data);
                        this.emit('message', packet.data);
                        break;
                }
            } else {
                debug('packet received with socket readyState "%s"', this.readyState);
            }
        };

        /**
         * Called upon handshake completion.
         *
         * @param {Object} handshake obj
         * @api private
         */

        Socket.prototype.onHandshake = function (data) {
            this.emit('handshake', data);
            this.id = data.sid;
            this.transport.query.sid = data.sid;
            this.upgrades = this.filterUpgrades(data.upgrades);
            this.pingInterval = data.pingInterval;
            this.pingTimeout = data.pingTimeout;
            this.onOpen();
            // In case open handler closes socket
            if ('closed' === this.readyState) return;
            this.setPing();

            // Prolong liveness of socket on heartbeat
            this.removeListener('heartbeat', this.onHeartbeat);
            this.on('heartbeat', this.onHeartbeat);
        };

        /**
         * Resets ping timeout.
         *
         * @api private
         */

        Socket.prototype.onHeartbeat = function (timeout) {
            clearTimeout(this.pingTimeoutTimer);
            var self = this;
            self.pingTimeoutTimer = setTimeout(function () {
                if ('closed' === self.readyState) return;
                self.onClose('ping timeout');
            }, timeout || (self.pingInterval + self.pingTimeout));
        };

        /**
         * Pings server every `this.pingInterval` and expects response
         * within `this.pingTimeout` or closes connection.
         *
         * @api private
         */

        Socket.prototype.setPing = function () {
            var self = this;
            clearTimeout(self.pingIntervalTimer);
            self.pingIntervalTimer = setTimeout(function () {
                debug('writing ping packet - expecting pong within %sms', self.pingTimeout);
                self.ping();
                self.onHeartbeat(self.pingTimeout);
            }, self.pingInterval);
        };

        /**
         * Sends a ping packet.
         *
         * @api private
         */

        Socket.prototype.ping = function () {
            var self = this;
            this.sendPacket('ping', function () {
                self.emit('ping');
            });
        };

        /**
         * Called on `drain` event
         *
         * @api private
         */

        Socket.prototype.onDrain = function () {
            this.writeBuffer.splice(0, this.prevBufferLen);

            // setting prevBufferLen = 0 is very important
            // for example, when upgrading, upgrade packet is sent over,
            // and a nonzero prevBufferLen could cause problems on `drain`
            this.prevBufferLen = 0;

            if (0 === this.writeBuffer.length) {
                this.emit('drain');
            } else {
                this.flush();
            }
        };

        /**
         * Flush write buffers.
         *
         * @api private
         */

        Socket.prototype.flush = function () {
            if ('closed' !== this.readyState && this.transport.writable &&
                !this.upgrading && this.writeBuffer.length) {
                debug('flushing %d packets in socket', this.writeBuffer.length);
                this.transport.send(this.writeBuffer);
                // keep track of current length of writeBuffer
                // splice writeBuffer and callbackBuffer on `drain`
                this.prevBufferLen = this.writeBuffer.length;
                this.emit('flush');
            }
        };

        /**
         * Sends a message.
         *
         * @param {String} message.
         * @param {Function} callback function.
         * @param {Object} options.
         * @return {Socket} for chaining.
         * @api public
         */

        Socket.prototype.write =
            Socket.prototype.send = function (msg, options, fn) {
                this.sendPacket('message', msg, options, fn);
                return this;
            };

        /**
         * Sends a packet.
         *
         * @param {String} packet type.
         * @param {String} data.
         * @param {Object} options.
         * @param {Function} callback function.
         * @api private
         */

        Socket.prototype.sendPacket = function (type, data, options, fn) {
            if ('function' === typeof data) {
                fn = data;
                data = undefined;
            }

            if ('function' === typeof options) {
                fn = options;
                options = null;
            }

            if ('closing' === this.readyState || 'closed' === this.readyState) {
                return;
            }

            options = options || {};
            options.compress = false !== options.compress;

            var packet = {
                type: type,
                data: data,
                options: options
            };
            this.emit('packetCreate', packet);
            this.writeBuffer.push(packet);
            if (fn) this.once('flush', fn);
            this.flush();
        };

        /**
         * Closes the connection.
         *
         * @api private
         */

        Socket.prototype.close = function () {
            if ('opening' === this.readyState || 'open' === this.readyState) {
                this.readyState = 'closing';

                var self = this;

                if (this.writeBuffer.length) {
                    this.once('drain', function () {
                        if (this.upgrading) {
                            waitForUpgrade();
                        } else {
                            close();
                        }
                    });
                } else if (this.upgrading) {
                    waitForUpgrade();
                } else {
                    close();
                }
            }

            function close () {
                self.onClose('forced close');
                debug('socket closing - telling transport to close');
                self.transport.close();
            }

            function cleanupAndClose () {
                self.removeListener('upgrade', cleanupAndClose);
                self.removeListener('upgradeError', cleanupAndClose);
                close();
            }

            function waitForUpgrade () {
                // wait for upgrade to finish since we can't send packets while pausing a transport
                self.once('upgrade', cleanupAndClose);
                self.once('upgradeError', cleanupAndClose);
            }

            return this;
        };

        /**
         * Called upon transport error
         *
         * @api private
         */

        Socket.prototype.onError = function (err) {
            debug('socket error %j', err);
            Socket.priorWebsocketSuccess = false;
            this.emit('error', err);
            this.onClose('transport error', err);
        };

        /**
         * Called upon transport close.
         *
         * @api private
         */

        Socket.prototype.onClose = function (reason, desc) {
            if ('opening' === this.readyState || 'open' === this.readyState || 'closing' === this.readyState) {
                debug('socket close with reason: "%s"', reason);
                var self = this;

                // clear timers
                clearTimeout(this.pingIntervalTimer);
                clearTimeout(this.pingTimeoutTimer);

                // stop event from firing again for transport
                this.transport.removeAllListeners('close');

                // ensure transport won't stay open
                this.transport.close();

                // ignore further transport communication
                this.transport.removeAllListeners();

                // set ready state
                this.readyState = 'closed';

                // clear session id
                this.id = null;

                // emit close event
                this.emit('close', reason, desc);

                // clean buffers after, so users can still
                // grab the buffers on `close` event
                self.writeBuffer = [];
                self.prevBufferLen = 0;
            }
        };

        /**
         * Filters upgrades, returning only those matching client transports.
         *
         * @param {Array} server upgrades
         * @api private
         *
         */

        Socket.prototype.filterUpgrades = function (upgrades) {
            var filteredUpgrades = [];
            for (var i = 0, j = upgrades.length; i < j; i++) {
                if (~index(this.transports, upgrades[i])) filteredUpgrades.push(upgrades[i]);
            }
            return filteredUpgrades;
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transport.js":
    /*!********************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transport.js ***!
  \********************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module dependencies.
         */

        var parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");

        /**
         * Module exports.
         */

        module.exports = Transport;

        /**
         * Transport abstract constructor.
         *
         * @param {Object} options.
         * @api private
         */

        function Transport (opts) {
            this.path = opts.path;
            this.hostname = opts.hostname;
            this.port = opts.port;
            this.secure = opts.secure;
            this.query = opts.query;
            this.timestampParam = opts.timestampParam;
            this.timestampRequests = opts.timestampRequests;
            this.readyState = '';
            this.agent = opts.agent || false;
            this.socket = opts.socket;
            this.enablesXDR = opts.enablesXDR;
            this.withCredentials = opts.withCredentials;

            // SSL options for Node.js client
            this.pfx = opts.pfx;
            this.key = opts.key;
            this.passphrase = opts.passphrase;
            this.cert = opts.cert;
            this.ca = opts.ca;
            this.ciphers = opts.ciphers;
            this.rejectUnauthorized = opts.rejectUnauthorized;
            this.forceNode = opts.forceNode;

            // results of ReactNative environment detection
            this.isReactNative = opts.isReactNative;

            // other options for Node.js client
            this.extraHeaders = opts.extraHeaders;
            this.localAddress = opts.localAddress;
        }

        /**
         * Mix in `Emitter`.
         */

        Emitter(Transport.prototype);

        /**
         * Emits an error.
         *
         * @param {String} str
         * @return {Transport} for chaining
         * @api public
         */

        Transport.prototype.onError = function (msg, desc) {
            var err = new Error(msg);
            err.type = 'TransportError';
            err.description = desc;
            this.emit('error', err);
            return this;
        };

        /**
         * Opens the transport.
         *
         * @api public
         */

        Transport.prototype.open = function () {
            if ('closed' === this.readyState || '' === this.readyState) {
                this.readyState = 'opening';
                this.doOpen();
            }

            return this;
        };

        /**
         * Closes the transport.
         *
         * @api private
         */

        Transport.prototype.close = function () {
            if ('opening' === this.readyState || 'open' === this.readyState) {
                this.doClose();
                this.onClose();
            }

            return this;
        };

        /**
         * Sends multiple packets.
         *
         * @param {Array} packets
         * @api private
         */

        Transport.prototype.send = function (packets) {
            if ('open' === this.readyState) {
                this.write(packets);
            } else {
                throw new Error('Transport not open');
            }
        };

        /**
         * Called upon open
         *
         * @api private
         */

        Transport.prototype.onOpen = function () {
            this.readyState = 'open';
            this.writable = true;
            this.emit('open');
        };

        /**
         * Called with data.
         *
         * @param {String} data
         * @api private
         */

        Transport.prototype.onData = function (data) {
            var packet = parser.decodePacket(data, this.socket.binaryType);
            this.onPacket(packet);
        };

        /**
         * Called with a decoded packet.
         */

        Transport.prototype.onPacket = function (packet) {
            this.emit('packet', packet);
        };

        /**
         * Called upon close.
         *
         * @api private
         */

        Transport.prototype.onClose = function () {
            this.readyState = 'closed';
            this.emit('close');
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transports/index.js":
    /*!***************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transports/index.js ***!
  \***************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module dependencies
         */

        var XMLHttpRequest = __webpack_require__(/*! xmlhttprequest-ssl */ "./node_modules/engine.io-client/lib/xmlhttprequest.js");
        var XHR = __webpack_require__(/*! ./polling-xhr */ "./node_modules/engine.io-client/lib/transports/polling-xhr.js");
        var JSONP = __webpack_require__(/*! ./polling-jsonp */ "./node_modules/engine.io-client/lib/transports/polling-jsonp.js");
        var websocket = __webpack_require__(/*! ./websocket */ "./node_modules/engine.io-client/lib/transports/websocket.js");

        /**
         * Export transports.
         */

        exports.polling = polling;
        exports.websocket = websocket;

        /**
         * Polling transport polymorphic constructor.
         * Decides on xhr vs jsonp based on feature detection.
         *
         * @api private
         */

        function polling (opts) {
            var xhr;
            var xd = false;
            var xs = false;
            var jsonp = false !== opts.jsonp;

            if (typeof location !== 'undefined') {
                var isSSL = 'https:' === location.protocol;
                var port = location.port;

                // some user agents have empty `location.port`
                if (!port) {
                    port = isSSL ? 443 : 80;
                }

                xd = opts.hostname !== location.hostname || port !== opts.port;
                xs = opts.secure !== isSSL;
            }

            opts.xdomain = xd;
            opts.xscheme = xs;
            xhr = new XMLHttpRequest(opts);

            if ('open' in xhr && !opts.forceJSONP) {
                return new XHR(opts);
            } else {
                if (!jsonp) throw new Error('JSONP disabled');
                return new JSONP(opts);
            }
        }


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transports/polling-jsonp.js":
    /*!***********************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transports/polling-jsonp.js ***!
  \***********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module requirements.
         */

        var Polling = __webpack_require__(/*! ./polling */ "./node_modules/engine.io-client/lib/transports/polling.js");
        var inherit = __webpack_require__(/*! component-inherit */ "./node_modules/component-inherit/index.js");
        var globalThis = __webpack_require__(/*! ../globalThis */ "./node_modules/engine.io-client/lib/globalThis.browser.js");

        /**
         * Module exports.
         */

        module.exports = JSONPPolling;

        /**
         * Cached regular expressions.
         */

        var rNewline = /\n/g;
        var rEscapedNewline = /\\n/g;

        /**
         * Global JSONP callbacks.
         */

        var callbacks;

        /**
         * Noop.
         */

        function empty () { }

        /**
         * JSONP Polling constructor.
         *
         * @param {Object} opts.
         * @api public
         */

        function JSONPPolling (opts) {
            Polling.call(this, opts);

            this.query = this.query || {};

            // define global callbacks array if not present
            // we do this here (lazily) to avoid unneeded global pollution
            if (!callbacks) {
                // we need to consider multiple engines in the same page
                callbacks = globalThis.___eio = (globalThis.___eio || []);
            }

            // callback identifier
            this.index = callbacks.length;

            // add callback to jsonp global
            var self = this;
            callbacks.push(function (msg) {
                self.onData(msg);
            });

            // append to query string
            this.query.j = this.index;

            // prevent spurious errors from being emitted when the window is unloaded
            if (typeof addEventListener === 'function') {
                addEventListener('beforeunload', function () {
                    if (self.script) self.script.onerror = empty;
                }, false);
            }
        }

        /**
         * Inherits from Polling.
         */

        inherit(JSONPPolling, Polling);

        /*
 * JSONP only supports binary as base64 encoded strings
 */

        JSONPPolling.prototype.supportsBinary = false;

        /**
         * Closes the socket.
         *
         * @api private
         */

        JSONPPolling.prototype.doClose = function () {
            if (this.script) {
                this.script.parentNode.removeChild(this.script);
                this.script = null;
            }

            if (this.form) {
                this.form.parentNode.removeChild(this.form);
                this.form = null;
                this.iframe = null;
            }

            Polling.prototype.doClose.call(this);
        };

        /**
         * Starts a poll cycle.
         *
         * @api private
         */

        JSONPPolling.prototype.doPoll = function () {
            var self = this;
            var script = document.createElement('script');

            if (this.script) {
                this.script.parentNode.removeChild(this.script);
                this.script = null;
            }

            script.async = true;
            script.src = this.uri();
            script.onerror = function (e) {
                self.onError('jsonp poll error', e);
            };

            var insertAt = document.getElementsByTagName('script')[0];
            if (insertAt) {
                insertAt.parentNode.insertBefore(script, insertAt);
            } else {
                (document.head || document.body).appendChild(script);
            }
            this.script = script;

            var isUAgecko = 'undefined' !== typeof navigator && /gecko/i.test(navigator.userAgent);

            if (isUAgecko) {
                setTimeout(function () {
                    var iframe = document.createElement('iframe');
                    document.body.appendChild(iframe);
                    document.body.removeChild(iframe);
                }, 100);
            }
        };

        /**
         * Writes with a hidden iframe.
         *
         * @param {String} data to send
         * @param {Function} called upon flush.
         * @api private
         */

        JSONPPolling.prototype.doWrite = function (data, fn) {
            var self = this;

            if (!this.form) {
                var form = document.createElement('form');
                var area = document.createElement('textarea');
                var id = this.iframeId = 'eio_iframe_' + this.index;
                var iframe;

                form.className = 'socketio';
                form.style.position = 'absolute';
                form.style.top = '-1000px';
                form.style.left = '-1000px';
                form.target = id;
                form.method = 'POST';
                form.setAttribute('accept-charset', 'utf-8');
                area.name = 'd';
                form.appendChild(area);
                document.body.appendChild(form);

                this.form = form;
                this.area = area;
            }

            this.form.action = this.uri();

            function complete () {
                initIframe();
                fn();
            }

            function initIframe () {
                if (self.iframe) {
                    try {
                        self.form.removeChild(self.iframe);
                    } catch (e) {
                        self.onError('jsonp polling iframe removal error', e);
                    }
                }

                try {
                    // ie6 dynamic iframes with target="" support (thanks Chris Lambacher)
                    var html = '<iframe src="javascript:0" name="' + self.iframeId + '">';
                    iframe = document.createElement(html);
                } catch (e) {
                    iframe = document.createElement('iframe');
                    iframe.name = self.iframeId;
                    iframe.src = 'javascript:0';
                }

                iframe.id = self.iframeId;

                self.form.appendChild(iframe);
                self.iframe = iframe;
            }

            initIframe();

            // escape \n to prevent it from being converted into \r\n by some UAs
            // double escaping is required for escaped new lines because unescaping of new lines can be done safely on server-side
            data = data.replace(rEscapedNewline, '\\\n');
            this.area.value = data.replace(rNewline, '\\n');

            try {
                this.form.submit();
            } catch (e) {}

            if (this.iframe.attachEvent) {
                this.iframe.onreadystatechange = function () {
                    if (self.iframe.readyState === 'complete') {
                        complete();
                    }
                };
            } else {
                this.iframe.onload = complete;
            }
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transports/polling-xhr.js":
    /*!*********************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transports/polling-xhr.js ***!
  \*********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /* global attachEvent */

        /**
         * Module requirements.
         */

        var XMLHttpRequest = __webpack_require__(/*! xmlhttprequest-ssl */ "./node_modules/engine.io-client/lib/xmlhttprequest.js");
        var Polling = __webpack_require__(/*! ./polling */ "./node_modules/engine.io-client/lib/transports/polling.js");
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");
        var inherit = __webpack_require__(/*! component-inherit */ "./node_modules/component-inherit/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('engine.io-client:polling-xhr');
        var globalThis = __webpack_require__(/*! ../globalThis */ "./node_modules/engine.io-client/lib/globalThis.browser.js");

        /**
         * Module exports.
         */

        module.exports = XHR;
        module.exports.Request = Request;

        /**
         * Empty function
         */

        function empty () {}

        /**
         * XHR Polling constructor.
         *
         * @param {Object} opts
         * @api public
         */

        function XHR (opts) {
            Polling.call(this, opts);
            this.requestTimeout = opts.requestTimeout;
            this.extraHeaders = opts.extraHeaders;

            if (typeof location !== 'undefined') {
                var isSSL = 'https:' === location.protocol;
                var port = location.port;

                // some user agents have empty `location.port`
                if (!port) {
                    port = isSSL ? 443 : 80;
                }

                this.xd = (typeof location !== 'undefined' && opts.hostname !== location.hostname) ||
                    port !== opts.port;
                this.xs = opts.secure !== isSSL;
            }
        }

        /**
         * Inherits from Polling.
         */

        inherit(XHR, Polling);

        /**
         * XHR supports binary
         */

        XHR.prototype.supportsBinary = true;

        /**
         * Creates a request.
         *
         * @param {String} method
         * @api private
         */

        XHR.prototype.request = function (opts) {
            opts = opts || {};
            opts.uri = this.uri();
            opts.xd = this.xd;
            opts.xs = this.xs;
            opts.agent = this.agent || false;
            opts.supportsBinary = this.supportsBinary;
            opts.enablesXDR = this.enablesXDR;
            opts.withCredentials = this.withCredentials;

            // SSL options for Node.js client
            opts.pfx = this.pfx;
            opts.key = this.key;
            opts.passphrase = this.passphrase;
            opts.cert = this.cert;
            opts.ca = this.ca;
            opts.ciphers = this.ciphers;
            opts.rejectUnauthorized = this.rejectUnauthorized;
            opts.requestTimeout = this.requestTimeout;

            // other options for Node.js client
            opts.extraHeaders = this.extraHeaders;

            return new Request(opts);
        };

        /**
         * Sends data.
         *
         * @param {String} data to send.
         * @param {Function} called upon flush.
         * @api private
         */

        XHR.prototype.doWrite = function (data, fn) {
            var isBinary = typeof data !== 'string' && data !== undefined;
            var req = this.request({ method: 'POST', data: data, isBinary: isBinary });
            var self = this;
            req.on('success', fn);
            req.on('error', function (err) {
                self.onError('xhr post error', err);
            });
            this.sendXhr = req;
        };

        /**
         * Starts a poll cycle.
         *
         * @api private
         */

        XHR.prototype.doPoll = function () {
            debug('xhr poll');
            var req = this.request();
            var self = this;
            req.on('data', function (data) {
                self.onData(data);
            });
            req.on('error', function (err) {
                self.onError('xhr poll error', err);
            });
            this.pollXhr = req;
        };

        /**
         * Request constructor
         *
         * @param {Object} options
         * @api public
         */

        function Request (opts) {
            this.method = opts.method || 'GET';
            this.uri = opts.uri;
            this.xd = !!opts.xd;
            this.xs = !!opts.xs;
            this.async = false !== opts.async;
            this.data = undefined !== opts.data ? opts.data : null;
            this.agent = opts.agent;
            this.isBinary = opts.isBinary;
            this.supportsBinary = opts.supportsBinary;
            this.enablesXDR = opts.enablesXDR;
            this.withCredentials = opts.withCredentials;
            this.requestTimeout = opts.requestTimeout;

            // SSL options for Node.js client
            this.pfx = opts.pfx;
            this.key = opts.key;
            this.passphrase = opts.passphrase;
            this.cert = opts.cert;
            this.ca = opts.ca;
            this.ciphers = opts.ciphers;
            this.rejectUnauthorized = opts.rejectUnauthorized;

            // other options for Node.js client
            this.extraHeaders = opts.extraHeaders;

            this.create();
        }

        /**
         * Mix in `Emitter`.
         */

        Emitter(Request.prototype);

        /**
         * Creates the XHR object and sends the request.
         *
         * @api private
         */

        Request.prototype.create = function () {
            var opts = { agent: this.agent, xdomain: this.xd, xscheme: this.xs, enablesXDR: this.enablesXDR };

            // SSL options for Node.js client
            opts.pfx = this.pfx;
            opts.key = this.key;
            opts.passphrase = this.passphrase;
            opts.cert = this.cert;
            opts.ca = this.ca;
            opts.ciphers = this.ciphers;
            opts.rejectUnauthorized = this.rejectUnauthorized;

            var xhr = this.xhr = new XMLHttpRequest(opts);
            var self = this;

            try {
                debug('xhr open %s: %s', this.method, this.uri);
                xhr.open(this.method, this.uri, this.async);
                try {
                    if (this.extraHeaders) {
                        xhr.setDisableHeaderCheck && xhr.setDisableHeaderCheck(true);
                        for (var i in this.extraHeaders) {
                            if (this.extraHeaders.hasOwnProperty(i)) {
                                xhr.setRequestHeader(i, this.extraHeaders[i]);
                            }
                        }
                    }
                } catch (e) {}

                if ('POST' === this.method) {
                    try {
                        if (this.isBinary) {
                            xhr.setRequestHeader('Content-type', 'application/octet-stream');
                        } else {
                            xhr.setRequestHeader('Content-type', 'text/plain;charset=UTF-8');
                        }
                    } catch (e) {}
                }

                try {
                    xhr.setRequestHeader('Accept', '*/*');
                } catch (e) {}

                // ie6 check
                if ('withCredentials' in xhr) {
                    xhr.withCredentials = this.withCredentials;
                }

                if (this.requestTimeout) {
                    xhr.timeout = this.requestTimeout;
                }

                if (this.hasXDR()) {
                    xhr.onload = function () {
                        self.onLoad();
                    };
                    xhr.onerror = function () {
                        self.onError(xhr.responseText);
                    };
                } else {
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 2) {
                            try {
                                var contentType = xhr.getResponseHeader('Content-Type');
                                if (self.supportsBinary && contentType === 'application/octet-stream' || contentType === 'application/octet-stream; charset=UTF-8') {
                                    xhr.responseType = 'arraybuffer';
                                }
                            } catch (e) {}
                        }
                        if (4 !== xhr.readyState) return;
                        if (200 === xhr.status || 1223 === xhr.status) {
                            self.onLoad();
                        } else {
                            // make sure the `error` event handler that's user-set
                            // does not throw in the same tick and gets caught here
                            setTimeout(function () {
                                self.onError(typeof xhr.status === 'number' ? xhr.status : 0);
                            }, 0);
                        }
                    };
                }

                debug('xhr data %s', this.data);
                xhr.send(this.data);
            } catch (e) {
                // Need to defer since .create() is called directly fhrom the constructor
                // and thus the 'error' event can only be only bound *after* this exception
                // occurs.  Therefore, also, we cannot throw here at all.
                setTimeout(function () {
                    self.onError(e);
                }, 0);
                return;
            }

            if (typeof document !== 'undefined') {
                this.index = Request.requestsCount++;
                Request.requests[this.index] = this;
            }
        };

        /**
         * Called upon successful response.
         *
         * @api private
         */

        Request.prototype.onSuccess = function () {
            this.emit('success');
            this.cleanup();
        };

        /**
         * Called if we have data.
         *
         * @api private
         */

        Request.prototype.onData = function (data) {
            this.emit('data', data);
            this.onSuccess();
        };

        /**
         * Called upon error.
         *
         * @api private
         */

        Request.prototype.onError = function (err) {
            this.emit('error', err);
            this.cleanup(true);
        };

        /**
         * Cleans up house.
         *
         * @api private
         */

        Request.prototype.cleanup = function (fromError) {
            if ('undefined' === typeof this.xhr || null === this.xhr) {
                return;
            }
            // xmlhttprequest
            if (this.hasXDR()) {
                this.xhr.onload = this.xhr.onerror = empty;
            } else {
                this.xhr.onreadystatechange = empty;
            }

            if (fromError) {
                try {
                    this.xhr.abort();
                } catch (e) {}
            }

            if (typeof document !== 'undefined') {
                delete Request.requests[this.index];
            }

            this.xhr = null;
        };

        /**
         * Called upon load.
         *
         * @api private
         */

        Request.prototype.onLoad = function () {
            var data;
            try {
                var contentType;
                try {
                    contentType = this.xhr.getResponseHeader('Content-Type');
                } catch (e) {}
                if (contentType === 'application/octet-stream' || contentType === 'application/octet-stream; charset=UTF-8') {
                    data = this.xhr.response || this.xhr.responseText;
                } else {
                    data = this.xhr.responseText;
                }
            } catch (e) {
                this.onError(e);
            }
            if (null != data) {
                this.onData(data);
            }
        };

        /**
         * Check if it has XDomainRequest.
         *
         * @api private
         */

        Request.prototype.hasXDR = function () {
            return typeof XDomainRequest !== 'undefined' && !this.xs && this.enablesXDR;
        };

        /**
         * Aborts the request.
         *
         * @api public
         */

        Request.prototype.abort = function () {
            this.cleanup();
        };

        /**
         * Aborts pending requests when unloading the window. This is needed to prevent
         * memory leaks (e.g. when using IE) and to ensure that no spurious error is
         * emitted.
         */

        Request.requestsCount = 0;
        Request.requests = {};

        if (typeof document !== 'undefined') {
            if (typeof attachEvent === 'function') {
                attachEvent('onunload', unloadHandler);
            } else if (typeof addEventListener === 'function') {
                var terminationEvent = 'onpagehide' in globalThis ? 'pagehide' : 'unload';
                addEventListener(terminationEvent, unloadHandler, false);
            }
        }

        function unloadHandler () {
            for (var i in Request.requests) {
                if (Request.requests.hasOwnProperty(i)) {
                    Request.requests[i].abort();
                }
            }
        }


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transports/polling.js":
    /*!*****************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transports/polling.js ***!
  \*****************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module dependencies.
         */

        var Transport = __webpack_require__(/*! ../transport */ "./node_modules/engine.io-client/lib/transport.js");
        var parseqs = __webpack_require__(/*! parseqs */ "./node_modules/engine.io-client/node_modules/parseqs/index.js");
        var parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");
        var inherit = __webpack_require__(/*! component-inherit */ "./node_modules/component-inherit/index.js");
        var yeast = __webpack_require__(/*! yeast */ "./node_modules/yeast/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('engine.io-client:polling');

        /**
         * Module exports.
         */

        module.exports = Polling;

        /**
         * Is XHR2 supported?
         */

        var hasXHR2 = (function () {
            var XMLHttpRequest = __webpack_require__(/*! xmlhttprequest-ssl */ "./node_modules/engine.io-client/lib/xmlhttprequest.js");
            var xhr = new XMLHttpRequest({ xdomain: false });
            return null != xhr.responseType;
        })();

        /**
         * Polling interface.
         *
         * @param {Object} opts
         * @api private
         */

        function Polling (opts) {
            var forceBase64 = (opts && opts.forceBase64);
            if (!hasXHR2 || forceBase64) {
                this.supportsBinary = false;
            }
            Transport.call(this, opts);
        }

        /**
         * Inherits from Transport.
         */

        inherit(Polling, Transport);

        /**
         * Transport name.
         */

        Polling.prototype.name = 'polling';

        /**
         * Opens the socket (triggers polling). We write a PING message to determine
         * when the transport is open.
         *
         * @api private
         */

        Polling.prototype.doOpen = function () {
            this.poll();
        };

        /**
         * Pauses polling.
         *
         * @param {Function} callback upon buffers are flushed and transport is paused
         * @api private
         */

        Polling.prototype.pause = function (onPause) {
            var self = this;

            this.readyState = 'pausing';

            function pause () {
                debug('paused');
                self.readyState = 'paused';
                onPause();
            }

            if (this.polling || !this.writable) {
                var total = 0;

                if (this.polling) {
                    debug('we are currently polling - waiting to pause');
                    total++;
                    this.once('pollComplete', function () {
                        debug('pre-pause polling complete');
                        --total || pause();
                    });
                }

                if (!this.writable) {
                    debug('we are currently writing - waiting to pause');
                    total++;
                    this.once('drain', function () {
                        debug('pre-pause writing complete');
                        --total || pause();
                    });
                }
            } else {
                pause();
            }
        };

        /**
         * Starts polling cycle.
         *
         * @api public
         */

        Polling.prototype.poll = function () {
            debug('polling');
            this.polling = true;
            this.doPoll();
            this.emit('poll');
        };

        /**
         * Overloads onData to detect payloads.
         *
         * @api private
         */

        Polling.prototype.onData = function (data) {
            var self = this;
            debug('polling got data %s', data);
            var callback = function (packet, index, total) {
                // if its the first message we consider the transport open
                if ('opening' === self.readyState && packet.type === 'open') {
                    self.onOpen();
                }

                // if its a close packet, we close the ongoing requests
                if ('close' === packet.type) {
                    self.onClose();
                    return false;
                }

                // otherwise bypass onData and handle the message
                self.onPacket(packet);
            };

            // decode payload
            parser.decodePayload(data, this.socket.binaryType, callback);

            // if an event did not trigger closing
            if ('closed' !== this.readyState) {
                // if we got data we're not polling
                this.polling = false;
                this.emit('pollComplete');

                if ('open' === this.readyState) {
                    this.poll();
                } else {
                    debug('ignoring poll - transport state "%s"', this.readyState);
                }
            }
        };

        /**
         * For polling, send a close packet.
         *
         * @api private
         */

        Polling.prototype.doClose = function () {
            var self = this;

            function close () {
                debug('writing close packet');
                self.write([{ type: 'close' }]);
            }

            if ('open' === this.readyState) {
                debug('transport open - closing');
                close();
            } else {
                // in case we're trying to close while
                // handshaking is in progress (GH-164)
                debug('transport not open - deferring close');
                this.once('open', close);
            }
        };

        /**
         * Writes a packets payload.
         *
         * @param {Array} data packets
         * @param {Function} drain callback
         * @api private
         */

        Polling.prototype.write = function (packets) {
            var self = this;
            this.writable = false;
            var callbackfn = function () {
                self.writable = true;
                self.emit('drain');
            };

            parser.encodePayload(packets, this.supportsBinary, function (data) {
                self.doWrite(data, callbackfn);
            });
        };

        /**
         * Generates uri for connection.
         *
         * @api private
         */

        Polling.prototype.uri = function () {
            var query = this.query || {};
            var schema = this.secure ? 'https' : 'http';
            var port = '';

            // cache busting is forced
            if (false !== this.timestampRequests) {
                query[this.timestampParam] = yeast();
            }

            if (!this.supportsBinary && !query.sid) {
                query.b64 = 1;
            }

            query = parseqs.encode(query);

            // avoid port if default for schema
            if (this.port && (('https' === schema && Number(this.port) !== 443) ||
                ('http' === schema && Number(this.port) !== 80))) {
                port = ':' + this.port;
            }

            // prepend ? to query
            if (query.length) {
                query = '?' + query;
            }

            var ipv6 = this.hostname.indexOf(':') !== -1;
            return schema + '://' + (ipv6 ? '[' + this.hostname + ']' : this.hostname) + port + this.path + query;
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/transports/websocket.js":
    /*!*******************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/transports/websocket.js ***!
  \*******************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /* WEBPACK VAR INJECTION */(function(Buffer) {/**
         * Module dependencies.
         */

        var Transport = __webpack_require__(/*! ../transport */ "./node_modules/engine.io-client/lib/transport.js");
            var parser = __webpack_require__(/*! engine.io-parser */ "./node_modules/engine.io-parser/lib/browser.js");
            var parseqs = __webpack_require__(/*! parseqs */ "./node_modules/engine.io-client/node_modules/parseqs/index.js");
            var inherit = __webpack_require__(/*! component-inherit */ "./node_modules/component-inherit/index.js");
            var yeast = __webpack_require__(/*! yeast */ "./node_modules/yeast/index.js");
            var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('engine.io-client:websocket');

            var BrowserWebSocket, NodeWebSocket;

            if (typeof WebSocket !== 'undefined') {
                BrowserWebSocket = WebSocket;
            } else if (typeof self !== 'undefined') {
                BrowserWebSocket = self.WebSocket || self.MozWebSocket;
            }

            if (typeof window === 'undefined') {
                try {
                    NodeWebSocket = __webpack_require__(/*! ws */ 2);
                } catch (e) { }
            }

            /**
             * Get either the `WebSocket` or `MozWebSocket` globals
             * in the browser or try to resolve WebSocket-compatible
             * interface exposed by `ws` for Node-like environment.
             */

            var WebSocketImpl = BrowserWebSocket || NodeWebSocket;

            /**
             * Module exports.
             */

            module.exports = WS;

            /**
             * WebSocket transport constructor.
             *
             * @api {Object} connection options
             * @api public
             */

            function WS (opts) {
                var forceBase64 = (opts && opts.forceBase64);
                if (forceBase64) {
                    this.supportsBinary = false;
                }
                this.perMessageDeflate = opts.perMessageDeflate;
                this.usingBrowserWebSocket = BrowserWebSocket && !opts.forceNode;
                this.protocols = opts.protocols;
                if (!this.usingBrowserWebSocket) {
                    WebSocketImpl = NodeWebSocket;
                }
                Transport.call(this, opts);
            }

            /**
             * Inherits from Transport.
             */

            inherit(WS, Transport);

            /**
             * Transport name.
             *
             * @api public
             */

            WS.prototype.name = 'websocket';

            /*
 * WebSockets support binary
 */

            WS.prototype.supportsBinary = true;

            /**
             * Opens socket.
             *
             * @api private
             */

            WS.prototype.doOpen = function () {
                if (!this.check()) {
                    // let probe timeout
                    return;
                }

                var uri = this.uri();
                var protocols = this.protocols;

                var opts = {};

                if (!this.isReactNative) {
                    opts.agent = this.agent;
                    opts.perMessageDeflate = this.perMessageDeflate;

                    // SSL options for Node.js client
                    opts.pfx = this.pfx;
                    opts.key = this.key;
                    opts.passphrase = this.passphrase;
                    opts.cert = this.cert;
                    opts.ca = this.ca;
                    opts.ciphers = this.ciphers;
                    opts.rejectUnauthorized = this.rejectUnauthorized;
                }

                if (this.extraHeaders) {
                    opts.headers = this.extraHeaders;
                }
                if (this.localAddress) {
                    opts.localAddress = this.localAddress;
                }

                try {
                    this.ws =
                        this.usingBrowserWebSocket && !this.isReactNative
                            ? protocols
                            ? new WebSocketImpl(uri, protocols)
                            : new WebSocketImpl(uri)
                            : new WebSocketImpl(uri, protocols, opts);
                } catch (err) {
                    return this.emit('error', err);
                }

                if (this.ws.binaryType === undefined) {
                    this.supportsBinary = false;
                }

                if (this.ws.supports && this.ws.supports.binary) {
                    this.supportsBinary = true;
                    this.ws.binaryType = 'nodebuffer';
                } else {
                    this.ws.binaryType = 'arraybuffer';
                }

                this.addEventListeners();
            };

            /**
             * Adds event listeners to the socket
             *
             * @api private
             */

            WS.prototype.addEventListeners = function () {
                var self = this;

                this.ws.onopen = function () {
                    self.onOpen();
                };
                this.ws.onclose = function () {
                    self.onClose();
                };
                this.ws.onmessage = function (ev) {
                    self.onData(ev.data);
                };
                this.ws.onerror = function (e) {
                    self.onError('websocket error', e);
                };
            };

            /**
             * Writes data to socket.
             *
             * @param {Array} array of packets.
             * @api private
             */

            WS.prototype.write = function (packets) {
                var self = this;
                this.writable = false;

                // encodePacket efficient as it uses WS framing
                // no need for encodePayload
                var total = packets.length;
                for (var i = 0, l = total; i < l; i++) {
                    (function (packet) {
                        parser.encodePacket(packet, self.supportsBinary, function (data) {
                            if (!self.usingBrowserWebSocket) {
                                // always create a new object (GH-437)
                                var opts = {};
                                if (packet.options) {
                                    opts.compress = packet.options.compress;
                                }

                                if (self.perMessageDeflate) {
                                    var len = 'string' === typeof data ? Buffer.byteLength(data) : data.length;
                                    if (len < self.perMessageDeflate.threshold) {
                                        opts.compress = false;
                                    }
                                }
                            }

                            // Sometimes the websocket has already been closed but the browser didn't
                            // have a chance of informing us about it yet, in that case send will
                            // throw an error
                            try {
                                if (self.usingBrowserWebSocket) {
                                    // TypeError is thrown when passing the second argument on Safari
                                    self.ws.send(data);
                                } else {
                                    self.ws.send(data, opts);
                                }
                            } catch (e) {
                                debug('websocket closed before onclose event');
                            }

                            --total || done();
                        });
                    })(packets[i]);
                }

                function done () {
                    self.emit('flush');

                    // fake drain
                    // defer to next tick to allow Socket to clear writeBuffer
                    setTimeout(function () {
                        self.writable = true;
                        self.emit('drain');
                    }, 0);
                }
            };

            /**
             * Called upon close
             *
             * @api private
             */

            WS.prototype.onClose = function () {
                Transport.prototype.onClose.call(this);
            };

            /**
             * Closes socket.
             *
             * @api private
             */

            WS.prototype.doClose = function () {
                if (typeof this.ws !== 'undefined') {
                    this.ws.close();
                }
            };

            /**
             * Generates uri for connection.
             *
             * @api private
             */

            WS.prototype.uri = function () {
                var query = this.query || {};
                var schema = this.secure ? 'wss' : 'ws';
                var port = '';

                // avoid port if default for schema
                if (this.port && (('wss' === schema && Number(this.port) !== 443) ||
                    ('ws' === schema && Number(this.port) !== 80))) {
                    port = ':' + this.port;
                }

                // append timestamp to URI
                if (this.timestampRequests) {
                    query[this.timestampParam] = yeast();
                }

                // communicate binary support capabilities
                if (!this.supportsBinary) {
                    query.b64 = 1;
                }

                query = parseqs.encode(query);

                // prepend ? to query
                if (query.length) {
                    query = '?' + query;
                }

                var ipv6 = this.hostname.indexOf(':') !== -1;
                return schema + '://' + (ipv6 ? '[' + this.hostname + ']' : this.hostname) + port + this.path + query;
            };

            /**
             * Feature detection for WebSocket.
             *
             * @return {Boolean} whether this transport is available.
             * @api public
             */

            WS.prototype.check = function () {
                return !!WebSocketImpl && !('__initialize' in WebSocketImpl && this.name === WS.prototype.name);
            };

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../buffer/index.js */ "./node_modules/buffer/index.js").Buffer))

        /***/ }),

    /***/ "./node_modules/engine.io-client/lib/xmlhttprequest.js":
    /*!*************************************************************!*\
  !*** ./node_modules/engine.io-client/lib/xmlhttprequest.js ***!
  \*************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

// browser shim for xmlhttprequest module

        var hasCORS = __webpack_require__(/*! has-cors */ "./node_modules/has-cors/index.js");
        var globalThis = __webpack_require__(/*! ./globalThis */ "./node_modules/engine.io-client/lib/globalThis.browser.js");

        module.exports = function (opts) {
            var xdomain = opts.xdomain;

            // scheme must be same when usign XDomainRequest
            // http://blogs.msdn.com/b/ieinternals/archive/2010/05/13/xdomainrequest-restrictions-limitations-and-workarounds.aspx
            var xscheme = opts.xscheme;

            // XDomainRequest has a flow of not sending cookie, therefore it should be disabled as a default.
            // https://github.com/Automattic/engine.io-client/pull/217
            var enablesXDR = opts.enablesXDR;

            // XMLHttpRequest can be disabled on IE
            try {
                if ('undefined' !== typeof XMLHttpRequest && (!xdomain || hasCORS)) {
                    return new XMLHttpRequest();
                }
            } catch (e) { }

            // Use XDomainRequest for IE8 if enablesXDR is true
            // because loading bar keeps flashing when using jsonp-polling
            // https://github.com/yujiosaka/socke.io-ie8-loading-example
            try {
                if ('undefined' !== typeof XDomainRequest && !xscheme && enablesXDR) {
                    return new XDomainRequest();
                }
            } catch (e) { }

            if (!xdomain) {
                try {
                    return new globalThis[['Active'].concat('Object').join('X')]('Microsoft.XMLHTTP');
                } catch (e) { }
            }
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/node_modules/parseqs/index.js":
    /*!*********************************************************************!*\
  !*** ./node_modules/engine.io-client/node_modules/parseqs/index.js ***!
  \*********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Compiles a querystring
         * Returns string representation of the object
         *
         * @param {Object}
         * @api private
         */

        exports.encode = function (obj) {
            var str = '';

            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    if (str.length) str += '&';
                    str += encodeURIComponent(i) + '=' + encodeURIComponent(obj[i]);
                }
            }

            return str;
        };

        /**
         * Parses a simple querystring into an object
         *
         * @param {String} qs
         * @api private
         */

        exports.decode = function(qs){
            var qry = {};
            var pairs = qs.split('&');
            for (var i = 0, l = pairs.length; i < l; i++) {
                var pair = pairs[i].split('=');
                qry[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            return qry;
        };


        /***/ }),

    /***/ "./node_modules/engine.io-client/node_modules/parseuri/index.js":
    /*!**********************************************************************!*\
  !*** ./node_modules/engine.io-client/node_modules/parseuri/index.js ***!
  \**********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Parses an URI
         *
         * @author Steven Levithan <stevenlevithan.com> (MIT license)
         * @api private
         */

        var re = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;

        var parts = [
            'source', 'protocol', 'authority', 'userInfo', 'user', 'password', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'anchor'
        ];

        module.exports = function parseuri(str) {
            var src = str,
                b = str.indexOf('['),
                e = str.indexOf(']');

            if (b != -1 && e != -1) {
                str = str.substring(0, b) + str.substring(b, e).replace(/:/g, ';') + str.substring(e, str.length);
            }

            var m = re.exec(str || ''),
                uri = {},
                i = 14;

            while (i--) {
                uri[parts[i]] = m[i] || '';
            }

            if (b != -1 && e != -1) {
                uri.source = src;
                uri.host = uri.host.substring(1, uri.host.length - 1).replace(/;/g, ':');
                uri.authority = uri.authority.replace('[', '').replace(']', '').replace(/;/g, ':');
                uri.ipv6uri = true;
            }

            uri.pathNames = pathNames(uri, uri['path']);
            uri.queryKey = queryKey(uri, uri['query']);

            return uri;
        };

        function pathNames(obj, path) {
            var regx = /\/{2,9}/g,
                names = path.replace(regx, "/").split("/");

            if (path.substr(0, 1) == '/' || path.length === 0) {
                names.splice(0, 1);
            }
            if (path.substr(path.length - 1, 1) == '/') {
                names.splice(names.length - 1, 1);
            }

            return names;
        }

        function queryKey(uri, query) {
            var data = {};

            query.replace(/(?:^|&)([^&=]*)=?([^&]*)/g, function ($0, $1, $2) {
                if ($1) {
                    data[$1] = $2;
                }
            });

            return data;
        }


        /***/ }),

    /***/ "./node_modules/engine.io-parser/lib/browser.js":
    /*!******************************************************!*\
  !*** ./node_modules/engine.io-parser/lib/browser.js ***!
  \******************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /**
         * Module dependencies.
         */

        var keys = __webpack_require__(/*! ./keys */ "./node_modules/engine.io-parser/lib/keys.js");
        var hasBinary = __webpack_require__(/*! has-binary2 */ "./node_modules/has-binary2/index.js");
        var sliceBuffer = __webpack_require__(/*! arraybuffer.slice */ "./node_modules/arraybuffer.slice/index.js");
        var after = __webpack_require__(/*! after */ "./node_modules/after/index.js");
        var utf8 = __webpack_require__(/*! ./utf8 */ "./node_modules/engine.io-parser/lib/utf8.js");

        var base64encoder;
        if (typeof ArrayBuffer !== 'undefined') {
            base64encoder = __webpack_require__(/*! base64-arraybuffer */ "./node_modules/engine.io-parser/node_modules/base64-arraybuffer/lib/base64-arraybuffer.js");
        }

        /**
         * Check if we are running an android browser. That requires us to use
         * ArrayBuffer with polling transports...
         *
         * http://ghinda.net/jpeg-blob-ajax-android/
         */

        var isAndroid = typeof navigator !== 'undefined' && /Android/i.test(navigator.userAgent);

        /**
         * Check if we are running in PhantomJS.
         * Uploading a Blob with PhantomJS does not work correctly, as reported here:
         * https://github.com/ariya/phantomjs/issues/11395
         * @type boolean
         */
        var isPhantomJS = typeof navigator !== 'undefined' && /PhantomJS/i.test(navigator.userAgent);

        /**
         * When true, avoids using Blobs to encode payloads.
         * @type boolean
         */
        var dontSendBlobs = isAndroid || isPhantomJS;

        /**
         * Current protocol version.
         */

        exports.protocol = 3;

        /**
         * Packet types.
         */

        var packets = exports.packets = {
            open:     0    // non-ws
            , close:    1    // non-ws
            , ping:     2
            , pong:     3
            , message:  4
            , upgrade:  5
            , noop:     6
        };

        var packetslist = keys(packets);

        /**
         * Premade error packet.
         */

        var err = { type: 'error', data: 'parser error' };

        /**
         * Create a blob api even for blob builder when vendor prefixes exist
         */

        var Blob = __webpack_require__(/*! blob */ "./node_modules/blob/index.js");

        /**
         * Encodes a packet.
         *
         *     <packet type id> [ <data> ]
         *
         * Example:
         *
         *     5hello world
         *     3
         *     4
         *
         * Binary is encoded in an identical principle
         *
         * @api private
         */

        exports.encodePacket = function (packet, supportsBinary, utf8encode, callback) {
            if (typeof supportsBinary === 'function') {
                callback = supportsBinary;
                supportsBinary = false;
            }

            if (typeof utf8encode === 'function') {
                callback = utf8encode;
                utf8encode = null;
            }

            var data = (packet.data === undefined)
                ? undefined
                : packet.data.buffer || packet.data;

            if (typeof ArrayBuffer !== 'undefined' && data instanceof ArrayBuffer) {
                return encodeArrayBuffer(packet, supportsBinary, callback);
            } else if (typeof Blob !== 'undefined' && data instanceof Blob) {
                return encodeBlob(packet, supportsBinary, callback);
            }

            // might be an object with { base64: true, data: dataAsBase64String }
            if (data && data.base64) {
                return encodeBase64Object(packet, callback);
            }

            // Sending data as a utf-8 string
            var encoded = packets[packet.type];

            // data fragment is optional
            if (undefined !== packet.data) {
                encoded += utf8encode ? utf8.encode(String(packet.data), { strict: false }) : String(packet.data);
            }

            return callback('' + encoded);

        };

        function encodeBase64Object(packet, callback) {
            // packet data is an object { base64: true, data: dataAsBase64String }
            var message = 'b' + exports.packets[packet.type] + packet.data.data;
            return callback(message);
        }

        /**
         * Encode packet helpers for binary types
         */

        function encodeArrayBuffer(packet, supportsBinary, callback) {
            if (!supportsBinary) {
                return exports.encodeBase64Packet(packet, callback);
            }

            var data = packet.data;
            var contentArray = new Uint8Array(data);
            var resultBuffer = new Uint8Array(1 + data.byteLength);

            resultBuffer[0] = packets[packet.type];
            for (var i = 0; i < contentArray.length; i++) {
                resultBuffer[i+1] = contentArray[i];
            }

            return callback(resultBuffer.buffer);
        }

        function encodeBlobAsArrayBuffer(packet, supportsBinary, callback) {
            if (!supportsBinary) {
                return exports.encodeBase64Packet(packet, callback);
            }

            var fr = new FileReader();
            fr.onload = function() {
                exports.encodePacket({ type: packet.type, data: fr.result }, supportsBinary, true, callback);
            };
            return fr.readAsArrayBuffer(packet.data);
        }

        function encodeBlob(packet, supportsBinary, callback) {
            if (!supportsBinary) {
                return exports.encodeBase64Packet(packet, callback);
            }

            if (dontSendBlobs) {
                return encodeBlobAsArrayBuffer(packet, supportsBinary, callback);
            }

            var length = new Uint8Array(1);
            length[0] = packets[packet.type];
            var blob = new Blob([length.buffer, packet.data]);

            return callback(blob);
        }

        /**
         * Encodes a packet with binary data in a base64 string
         *
         * @param {Object} packet, has `type` and `data`
         * @return {String} base64 encoded message
         */

        exports.encodeBase64Packet = function(packet, callback) {
            var message = 'b' + exports.packets[packet.type];
            if (typeof Blob !== 'undefined' && packet.data instanceof Blob) {
                var fr = new FileReader();
                fr.onload = function() {
                    var b64 = fr.result.split(',')[1];
                    callback(message + b64);
                };
                return fr.readAsDataURL(packet.data);
            }

            var b64data;
            try {
                b64data = String.fromCharCode.apply(null, new Uint8Array(packet.data));
            } catch (e) {
                // iPhone Safari doesn't let you apply with typed arrays
                var typed = new Uint8Array(packet.data);
                var basic = new Array(typed.length);
                for (var i = 0; i < typed.length; i++) {
                    basic[i] = typed[i];
                }
                b64data = String.fromCharCode.apply(null, basic);
            }
            message += btoa(b64data);
            return callback(message);
        };

        /**
         * Decodes a packet. Changes format to Blob if requested.
         *
         * @return {Object} with `type` and `data` (if any)
         * @api private
         */

        exports.decodePacket = function (data, binaryType, utf8decode) {
            if (data === undefined) {
                return err;
            }
            // String data
            if (typeof data === 'string') {
                if (data.charAt(0) === 'b') {
                    return exports.decodeBase64Packet(data.substr(1), binaryType);
                }

                if (utf8decode) {
                    data = tryDecode(data);
                    if (data === false) {
                        return err;
                    }
                }
                var type = data.charAt(0);

                if (Number(type) != type || !packetslist[type]) {
                    return err;
                }

                if (data.length > 1) {
                    return { type: packetslist[type], data: data.substring(1) };
                } else {
                    return { type: packetslist[type] };
                }
            }

            var asArray = new Uint8Array(data);
            var type = asArray[0];
            var rest = sliceBuffer(data, 1);
            if (Blob && binaryType === 'blob') {
                rest = new Blob([rest]);
            }
            return { type: packetslist[type], data: rest };
        };

        function tryDecode(data) {
            try {
                data = utf8.decode(data, { strict: false });
            } catch (e) {
                return false;
            }
            return data;
        }

        /**
         * Decodes a packet encoded in a base64 string
         *
         * @param {String} base64 encoded message
         * @return {Object} with `type` and `data` (if any)
         */

        exports.decodeBase64Packet = function(msg, binaryType) {
            var type = packetslist[msg.charAt(0)];
            if (!base64encoder) {
                return { type: type, data: { base64: true, data: msg.substr(1) } };
            }

            var data = base64encoder.decode(msg.substr(1));

            if (binaryType === 'blob' && Blob) {
                data = new Blob([data]);
            }

            return { type: type, data: data };
        };

        /**
         * Encodes multiple messages (payload).
         *
         *     <length>:data
         *
         * Example:
         *
         *     11:hello world2:hi
         *
         * If any contents are binary, they will be encoded as base64 strings. Base64
         * encoded strings are marked with a b before the length specifier
         *
         * @param {Array} packets
         * @api private
         */

        exports.encodePayload = function (packets, supportsBinary, callback) {
            if (typeof supportsBinary === 'function') {
                callback = supportsBinary;
                supportsBinary = null;
            }

            var isBinary = hasBinary(packets);

            if (supportsBinary && isBinary) {
                if (Blob && !dontSendBlobs) {
                    return exports.encodePayloadAsBlob(packets, callback);
                }

                return exports.encodePayloadAsArrayBuffer(packets, callback);
            }

            if (!packets.length) {
                return callback('0:');
            }

            function setLengthHeader(message) {
                return message.length + ':' + message;
            }

            function encodeOne(packet, doneCallback) {
                exports.encodePacket(packet, !isBinary ? false : supportsBinary, false, function(message) {
                    doneCallback(null, setLengthHeader(message));
                });
            }

            map(packets, encodeOne, function(err, results) {
                return callback(results.join(''));
            });
        };

        /**
         * Async array map using after
         */

        function map(ary, each, done) {
            var result = new Array(ary.length);
            var next = after(ary.length, done);

            var eachWithIndex = function(i, el, cb) {
                each(el, function(error, msg) {
                    result[i] = msg;
                    cb(error, result);
                });
            };

            for (var i = 0; i < ary.length; i++) {
                eachWithIndex(i, ary[i], next);
            }
        }

        /*
 * Decodes data when a payload is maybe expected. Possible binary contents are
 * decoded from their base64 representation
 *
 * @param {String} data, callback method
 * @api public
 */

        exports.decodePayload = function (data, binaryType, callback) {
            if (typeof data !== 'string') {
                return exports.decodePayloadAsBinary(data, binaryType, callback);
            }

            if (typeof binaryType === 'function') {
                callback = binaryType;
                binaryType = null;
            }

            var packet;
            if (data === '') {
                // parser error - ignoring payload
                return callback(err, 0, 1);
            }

            var length = '', n, msg;

            for (var i = 0, l = data.length; i < l; i++) {
                var chr = data.charAt(i);

                if (chr !== ':') {
                    length += chr;
                    continue;
                }

                if (length === '' || (length != (n = Number(length)))) {
                    // parser error - ignoring payload
                    return callback(err, 0, 1);
                }

                msg = data.substr(i + 1, n);

                if (length != msg.length) {
                    // parser error - ignoring payload
                    return callback(err, 0, 1);
                }

                if (msg.length) {
                    packet = exports.decodePacket(msg, binaryType, false);

                    if (err.type === packet.type && err.data === packet.data) {
                        // parser error in individual packet - ignoring payload
                        return callback(err, 0, 1);
                    }

                    var ret = callback(packet, i + n, l);
                    if (false === ret) return;
                }

                // advance cursor
                i += n;
                length = '';
            }

            if (length !== '') {
                // parser error - ignoring payload
                return callback(err, 0, 1);
            }

        };

        /**
         * Encodes multiple messages (payload) as binary.
         *
         * <1 = binary, 0 = string><number from 0-9><number from 0-9>[...]<number
         * 255><data>
         *
         * Example:
         * 1 3 255 1 2 3, if the binary contents are interpreted as 8 bit integers
         *
         * @param {Array} packets
         * @return {ArrayBuffer} encoded payload
         * @api private
         */

        exports.encodePayloadAsArrayBuffer = function(packets, callback) {
            if (!packets.length) {
                return callback(new ArrayBuffer(0));
            }

            function encodeOne(packet, doneCallback) {
                exports.encodePacket(packet, true, true, function(data) {
                    return doneCallback(null, data);
                });
            }

            map(packets, encodeOne, function(err, encodedPackets) {
                var totalLength = encodedPackets.reduce(function(acc, p) {
                    var len;
                    if (typeof p === 'string'){
                        len = p.length;
                    } else {
                        len = p.byteLength;
                    }
                    return acc + len.toString().length + len + 2; // string/binary identifier + separator = 2
                }, 0);

                var resultArray = new Uint8Array(totalLength);

                var bufferIndex = 0;
                encodedPackets.forEach(function(p) {
                    var isString = typeof p === 'string';
                    var ab = p;
                    if (isString) {
                        var view = new Uint8Array(p.length);
                        for (var i = 0; i < p.length; i++) {
                            view[i] = p.charCodeAt(i);
                        }
                        ab = view.buffer;
                    }

                    if (isString) { // not true binary
                        resultArray[bufferIndex++] = 0;
                    } else { // true binary
                        resultArray[bufferIndex++] = 1;
                    }

                    var lenStr = ab.byteLength.toString();
                    for (var i = 0; i < lenStr.length; i++) {
                        resultArray[bufferIndex++] = parseInt(lenStr[i]);
                    }
                    resultArray[bufferIndex++] = 255;

                    var view = new Uint8Array(ab);
                    for (var i = 0; i < view.length; i++) {
                        resultArray[bufferIndex++] = view[i];
                    }
                });

                return callback(resultArray.buffer);
            });
        };

        /**
         * Encode as Blob
         */

        exports.encodePayloadAsBlob = function(packets, callback) {
            function encodeOne(packet, doneCallback) {
                exports.encodePacket(packet, true, true, function(encoded) {
                    var binaryIdentifier = new Uint8Array(1);
                    binaryIdentifier[0] = 1;
                    if (typeof encoded === 'string') {
                        var view = new Uint8Array(encoded.length);
                        for (var i = 0; i < encoded.length; i++) {
                            view[i] = encoded.charCodeAt(i);
                        }
                        encoded = view.buffer;
                        binaryIdentifier[0] = 0;
                    }

                    var len = (encoded instanceof ArrayBuffer)
                        ? encoded.byteLength
                        : encoded.size;

                    var lenStr = len.toString();
                    var lengthAry = new Uint8Array(lenStr.length + 1);
                    for (var i = 0; i < lenStr.length; i++) {
                        lengthAry[i] = parseInt(lenStr[i]);
                    }
                    lengthAry[lenStr.length] = 255;

                    if (Blob) {
                        var blob = new Blob([binaryIdentifier.buffer, lengthAry.buffer, encoded]);
                        doneCallback(null, blob);
                    }
                });
            }

            map(packets, encodeOne, function(err, results) {
                return callback(new Blob(results));
            });
        };

        /*
 * Decodes data when a payload is maybe expected. Strings are decoded by
 * interpreting each byte as a key code for entries marked to start with 0. See
 * description of encodePayloadAsBinary
 *
 * @param {ArrayBuffer} data, callback method
 * @api public
 */

        exports.decodePayloadAsBinary = function (data, binaryType, callback) {
            if (typeof binaryType === 'function') {
                callback = binaryType;
                binaryType = null;
            }

            var bufferTail = data;
            var buffers = [];

            while (bufferTail.byteLength > 0) {
                var tailArray = new Uint8Array(bufferTail);
                var isString = tailArray[0] === 0;
                var msgLength = '';

                for (var i = 1; ; i++) {
                    if (tailArray[i] === 255) break;

                    // 310 = char length of Number.MAX_VALUE
                    if (msgLength.length > 310) {
                        return callback(err, 0, 1);
                    }

                    msgLength += tailArray[i];
                }

                bufferTail = sliceBuffer(bufferTail, 2 + msgLength.length);
                msgLength = parseInt(msgLength);

                var msg = sliceBuffer(bufferTail, 0, msgLength);
                if (isString) {
                    try {
                        msg = String.fromCharCode.apply(null, new Uint8Array(msg));
                    } catch (e) {
                        // iPhone Safari doesn't let you apply to typed arrays
                        var typed = new Uint8Array(msg);
                        msg = '';
                        for (var i = 0; i < typed.length; i++) {
                            msg += String.fromCharCode(typed[i]);
                        }
                    }
                }

                buffers.push(msg);
                bufferTail = sliceBuffer(bufferTail, msgLength);
            }

            var total = buffers.length;
            buffers.forEach(function(buffer, i) {
                callback(exports.decodePacket(buffer, binaryType, true), i, total);
            });
        };


        /***/ }),

    /***/ "./node_modules/engine.io-parser/lib/keys.js":
    /*!***************************************************!*\
  !*** ./node_modules/engine.io-parser/lib/keys.js ***!
  \***************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        /**
         * Gets the keys for an object.
         *
         * @return {Array} keys
         * @api private
         */

        module.exports = Object.keys || function keys (obj){
            var arr = [];
            var has = Object.prototype.hasOwnProperty;

            for (var i in obj) {
                if (has.call(obj, i)) {
                    arr.push(i);
                }
            }
            return arr;
        };


        /***/ }),

    /***/ "./node_modules/engine.io-parser/lib/utf8.js":
    /*!***************************************************!*\
  !*** ./node_modules/engine.io-parser/lib/utf8.js ***!
  \***************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /*! https://mths.be/utf8js v2.1.2 by @mathias */

        var stringFromCharCode = String.fromCharCode;

// Taken from https://mths.be/punycode
        function ucs2decode(string) {
            var output = [];
            var counter = 0;
            var length = string.length;
            var value;
            var extra;
            while (counter < length) {
                value = string.charCodeAt(counter++);
                if (value >= 0xD800 && value <= 0xDBFF && counter < length) {
                    // high surrogate, and there is a next character
                    extra = string.charCodeAt(counter++);
                    if ((extra & 0xFC00) == 0xDC00) { // low surrogate
                        output.push(((value & 0x3FF) << 10) + (extra & 0x3FF) + 0x10000);
                    } else {
                        // unmatched surrogate; only append this code unit, in case the next
                        // code unit is the high surrogate of a surrogate pair
                        output.push(value);
                        counter--;
                    }
                } else {
                    output.push(value);
                }
            }
            return output;
        }

// Taken from https://mths.be/punycode
        function ucs2encode(array) {
            var length = array.length;
            var index = -1;
            var value;
            var output = '';
            while (++index < length) {
                value = array[index];
                if (value > 0xFFFF) {
                    value -= 0x10000;
                    output += stringFromCharCode(value >>> 10 & 0x3FF | 0xD800);
                    value = 0xDC00 | value & 0x3FF;
                }
                output += stringFromCharCode(value);
            }
            return output;
        }

        function checkScalarValue(codePoint, strict) {
            if (codePoint >= 0xD800 && codePoint <= 0xDFFF) {
                if (strict) {
                    throw Error(
                        'Lone surrogate U+' + codePoint.toString(16).toUpperCase() +
                        ' is not a scalar value'
                    );
                }
                return false;
            }
            return true;
        }
        /*--------------------------------------------------------------------------*/

        function createByte(codePoint, shift) {
            return stringFromCharCode(((codePoint >> shift) & 0x3F) | 0x80);
        }

        function encodeCodePoint(codePoint, strict) {
            if ((codePoint & 0xFFFFFF80) == 0) { // 1-byte sequence
                return stringFromCharCode(codePoint);
            }
            var symbol = '';
            if ((codePoint & 0xFFFFF800) == 0) { // 2-byte sequence
                symbol = stringFromCharCode(((codePoint >> 6) & 0x1F) | 0xC0);
            }
            else if ((codePoint & 0xFFFF0000) == 0) { // 3-byte sequence
                if (!checkScalarValue(codePoint, strict)) {
                    codePoint = 0xFFFD;
                }
                symbol = stringFromCharCode(((codePoint >> 12) & 0x0F) | 0xE0);
                symbol += createByte(codePoint, 6);
            }
            else if ((codePoint & 0xFFE00000) == 0) { // 4-byte sequence
                symbol = stringFromCharCode(((codePoint >> 18) & 0x07) | 0xF0);
                symbol += createByte(codePoint, 12);
                symbol += createByte(codePoint, 6);
            }
            symbol += stringFromCharCode((codePoint & 0x3F) | 0x80);
            return symbol;
        }

        function utf8encode(string, opts) {
            opts = opts || {};
            var strict = false !== opts.strict;

            var codePoints = ucs2decode(string);
            var length = codePoints.length;
            var index = -1;
            var codePoint;
            var byteString = '';
            while (++index < length) {
                codePoint = codePoints[index];
                byteString += encodeCodePoint(codePoint, strict);
            }
            return byteString;
        }

        /*--------------------------------------------------------------------------*/

        function readContinuationByte() {
            if (byteIndex >= byteCount) {
                throw Error('Invalid byte index');
            }

            var continuationByte = byteArray[byteIndex] & 0xFF;
            byteIndex++;

            if ((continuationByte & 0xC0) == 0x80) {
                return continuationByte & 0x3F;
            }

            // If we end up here, it’s not a continuation byte
            throw Error('Invalid continuation byte');
        }

        function decodeSymbol(strict) {
            var byte1;
            var byte2;
            var byte3;
            var byte4;
            var codePoint;

            if (byteIndex > byteCount) {
                throw Error('Invalid byte index');
            }

            if (byteIndex == byteCount) {
                return false;
            }

            // Read first byte
            byte1 = byteArray[byteIndex] & 0xFF;
            byteIndex++;

            // 1-byte sequence (no continuation bytes)
            if ((byte1 & 0x80) == 0) {
                return byte1;
            }

            // 2-byte sequence
            if ((byte1 & 0xE0) == 0xC0) {
                byte2 = readContinuationByte();
                codePoint = ((byte1 & 0x1F) << 6) | byte2;
                if (codePoint >= 0x80) {
                    return codePoint;
                } else {
                    throw Error('Invalid continuation byte');
                }
            }

            // 3-byte sequence (may include unpaired surrogates)
            if ((byte1 & 0xF0) == 0xE0) {
                byte2 = readContinuationByte();
                byte3 = readContinuationByte();
                codePoint = ((byte1 & 0x0F) << 12) | (byte2 << 6) | byte3;
                if (codePoint >= 0x0800) {
                    return checkScalarValue(codePoint, strict) ? codePoint : 0xFFFD;
                } else {
                    throw Error('Invalid continuation byte');
                }
            }

            // 4-byte sequence
            if ((byte1 & 0xF8) == 0xF0) {
                byte2 = readContinuationByte();
                byte3 = readContinuationByte();
                byte4 = readContinuationByte();
                codePoint = ((byte1 & 0x07) << 0x12) | (byte2 << 0x0C) |
                    (byte3 << 0x06) | byte4;
                if (codePoint >= 0x010000 && codePoint <= 0x10FFFF) {
                    return codePoint;
                }
            }

            throw Error('Invalid UTF-8 detected');
        }

        var byteArray;
        var byteCount;
        var byteIndex;
        function utf8decode(byteString, opts) {
            opts = opts || {};
            var strict = false !== opts.strict;

            byteArray = ucs2decode(byteString);
            byteCount = byteArray.length;
            byteIndex = 0;
            var codePoints = [];
            var tmp;
            while ((tmp = decodeSymbol(strict)) !== false) {
                codePoints.push(tmp);
            }
            return ucs2encode(codePoints);
        }

        module.exports = {
            version: '2.1.2',
            encode: utf8encode,
            decode: utf8decode
        };


        /***/ }),

    /***/ "./node_modules/engine.io-parser/node_modules/base64-arraybuffer/lib/base64-arraybuffer.js":
    /*!*************************************************************************************************!*\
  !*** ./node_modules/engine.io-parser/node_modules/base64-arraybuffer/lib/base64-arraybuffer.js ***!
  \*************************************************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /*
 * base64-arraybuffer
 * https://github.com/niklasvh/base64-arraybuffer
 *
 * Copyright (c) 2012 Niklas von Hertzen
 * Licensed under the MIT license.
 */
        (function(chars){
            "use strict";

            exports.encode = function(arraybuffer) {
                var bytes = new Uint8Array(arraybuffer),
                    i, len = bytes.length, base64 = "";

                for (i = 0; i < len; i+=3) {
                    base64 += chars[bytes[i] >> 2];
                    base64 += chars[((bytes[i] & 3) << 4) | (bytes[i + 1] >> 4)];
                    base64 += chars[((bytes[i + 1] & 15) << 2) | (bytes[i + 2] >> 6)];
                    base64 += chars[bytes[i + 2] & 63];
                }

                if ((len % 3) === 2) {
                    base64 = base64.substring(0, base64.length - 1) + "=";
                } else if (len % 3 === 1) {
                    base64 = base64.substring(0, base64.length - 2) + "==";
                }

                return base64;
            };

            exports.decode =  function(base64) {
                var bufferLength = base64.length * 0.75,
                    len = base64.length, i, p = 0,
                    encoded1, encoded2, encoded3, encoded4;

                if (base64[base64.length - 1] === "=") {
                    bufferLength--;
                    if (base64[base64.length - 2] === "=") {
                        bufferLength--;
                    }
                }

                var arraybuffer = new ArrayBuffer(bufferLength),
                    bytes = new Uint8Array(arraybuffer);

                for (i = 0; i < len; i+=4) {
                    encoded1 = chars.indexOf(base64[i]);
                    encoded2 = chars.indexOf(base64[i+1]);
                    encoded3 = chars.indexOf(base64[i+2]);
                    encoded4 = chars.indexOf(base64[i+3]);

                    bytes[p++] = (encoded1 << 2) | (encoded2 >> 4);
                    bytes[p++] = ((encoded2 & 15) << 4) | (encoded3 >> 2);
                    bytes[p++] = ((encoded3 & 3) << 6) | (encoded4 & 63);
                }

                return arraybuffer;
            };
        })("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/");


        /***/ }),

    /***/ "./node_modules/has-binary2/index.js":
    /*!*******************************************!*\
  !*** ./node_modules/has-binary2/index.js ***!
  \*******************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /* WEBPACK VAR INJECTION */(function(Buffer) {/* global Blob File */

            /*
 * Module requirements.
 */

            var isArray = __webpack_require__(/*! isarray */ "./node_modules/has-binary2/node_modules/isarray/index.js");

            var toString = Object.prototype.toString;
            var withNativeBlob = typeof Blob === 'function' ||
                typeof Blob !== 'undefined' && toString.call(Blob) === '[object BlobConstructor]';
            var withNativeFile = typeof File === 'function' ||
                typeof File !== 'undefined' && toString.call(File) === '[object FileConstructor]';

            /**
             * Module exports.
             */

            module.exports = hasBinary;

            /**
             * Checks for binary data.
             *
             * Supports Buffer, ArrayBuffer, Blob and File.
             *
             * @param {Object} anything
             * @api public
             */

            function hasBinary (obj) {
                if (!obj || typeof obj !== 'object') {
                    return false;
                }

                if (isArray(obj)) {
                    for (var i = 0, l = obj.length; i < l; i++) {
                        if (hasBinary(obj[i])) {
                            return true;
                        }
                    }
                    return false;
                }

                if ((typeof Buffer === 'function' && Buffer.isBuffer && Buffer.isBuffer(obj)) ||
                    (typeof ArrayBuffer === 'function' && obj instanceof ArrayBuffer) ||
                    (withNativeBlob && obj instanceof Blob) ||
                    (withNativeFile && obj instanceof File)
                ) {
                    return true;
                }

                // see: https://github.com/Automattic/has-binary/pull/4
                if (obj.toJSON && typeof obj.toJSON === 'function' && arguments.length === 1) {
                    return hasBinary(obj.toJSON(), true);
                }

                for (var key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key) && hasBinary(obj[key])) {
                        return true;
                    }
                }

                return false;
            }

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../buffer/index.js */ "./node_modules/buffer/index.js").Buffer))

        /***/ }),

    /***/ "./node_modules/has-binary2/node_modules/isarray/index.js":
    /*!****************************************************************!*\
  !*** ./node_modules/has-binary2/node_modules/isarray/index.js ***!
  \****************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        var toString = {}.toString;

        module.exports = Array.isArray || function (arr) {
            return toString.call(arr) == '[object Array]';
        };


        /***/ }),

    /***/ "./node_modules/has-cors/index.js":
    /*!****************************************!*\
  !*** ./node_modules/has-cors/index.js ***!
  \****************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        /**
         * Module exports.
         *
         * Logic borrowed from Modernizr:
         *
         *   - https://github.com/Modernizr/Modernizr/blob/master/feature-detects/cors.js
         */

        try {
            module.exports = typeof XMLHttpRequest !== 'undefined' &&
                'withCredentials' in new XMLHttpRequest();
        } catch (err) {
            // if XMLHttp support is disabled in IE then it will throw
            // when trying to create
            module.exports = false;
        }


        /***/ }),

    /***/ "./node_modules/ieee754/index.js":
    /*!***************************************!*\
  !*** ./node_modules/ieee754/index.js ***!
  \***************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        exports.read = function (buffer, offset, isLE, mLen, nBytes) {
            var e, m
            var eLen = (nBytes * 8) - mLen - 1
            var eMax = (1 << eLen) - 1
            var eBias = eMax >> 1
            var nBits = -7
            var i = isLE ? (nBytes - 1) : 0
            var d = isLE ? -1 : 1
            var s = buffer[offset + i]

            i += d

            e = s & ((1 << (-nBits)) - 1)
            s >>= (-nBits)
            nBits += eLen
            for (; nBits > 0; e = (e * 256) + buffer[offset + i], i += d, nBits -= 8) {}

            m = e & ((1 << (-nBits)) - 1)
            e >>= (-nBits)
            nBits += mLen
            for (; nBits > 0; m = (m * 256) + buffer[offset + i], i += d, nBits -= 8) {}

            if (e === 0) {
                e = 1 - eBias
            } else if (e === eMax) {
                return m ? NaN : ((s ? -1 : 1) * Infinity)
            } else {
                m = m + Math.pow(2, mLen)
                e = e - eBias
            }
            return (s ? -1 : 1) * m * Math.pow(2, e - mLen)
        }

        exports.write = function (buffer, value, offset, isLE, mLen, nBytes) {
            var e, m, c
            var eLen = (nBytes * 8) - mLen - 1
            var eMax = (1 << eLen) - 1
            var eBias = eMax >> 1
            var rt = (mLen === 23 ? Math.pow(2, -24) - Math.pow(2, -77) : 0)
            var i = isLE ? 0 : (nBytes - 1)
            var d = isLE ? 1 : -1
            var s = value < 0 || (value === 0 && 1 / value < 0) ? 1 : 0

            value = Math.abs(value)

            if (isNaN(value) || value === Infinity) {
                m = isNaN(value) ? 1 : 0
                e = eMax
            } else {
                e = Math.floor(Math.log(value) / Math.LN2)
                if (value * (c = Math.pow(2, -e)) < 1) {
                    e--
                    c *= 2
                }
                if (e + eBias >= 1) {
                    value += rt / c
                } else {
                    value += rt * Math.pow(2, 1 - eBias)
                }
                if (value * c >= 2) {
                    e++
                    c /= 2
                }

                if (e + eBias >= eMax) {
                    m = 0
                    e = eMax
                } else if (e + eBias >= 1) {
                    m = ((value * c) - 1) * Math.pow(2, mLen)
                    e = e + eBias
                } else {
                    m = value * Math.pow(2, eBias - 1) * Math.pow(2, mLen)
                    e = 0
                }
            }

            for (; mLen >= 8; buffer[offset + i] = m & 0xff, i += d, m /= 256, mLen -= 8) {}

            e = (e << mLen) | m
            eLen += mLen
            for (; eLen > 0; buffer[offset + i] = e & 0xff, i += d, e /= 256, eLen -= 8) {}

            buffer[offset + i - d] |= s * 128
        }


        /***/ }),

    /***/ "./node_modules/indexof/index.js":
    /*!***************************************!*\
  !*** ./node_modules/indexof/index.js ***!
  \***************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        var indexOf = [].indexOf;

        module.exports = function(arr, obj){
            if (indexOf) return arr.indexOf(obj);
            for (var i = 0; i < arr.length; ++i) {
                if (arr[i] === obj) return i;
            }
            return -1;
        };

        /***/ }),

    /***/ "./node_modules/isarray/index.js":
    /*!***************************************!*\
  !*** ./node_modules/isarray/index.js ***!
  \***************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        var toString = {}.toString;

        module.exports = Array.isArray || function (arr) {
            return toString.call(arr) == '[object Array]';
        };


        /***/ }),

    /***/ "./node_modules/jquery/dist/jquery.js":
    /*!********************************************!*\
  !*** ./node_modules/jquery/dist/jquery.js ***!
  \********************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery JavaScript Library v3.5.1
 * https://jquery.com/
 *
 * Includes Sizzle.js
 * https://sizzlejs.com/
 *
 * Copyright JS Foundation and other contributors
 * Released under the MIT license
 * https://jquery.org/license
 *
 * Date: 2020-05-04T22:49Z
 */
        ( function( global, factory ) {

            "use strict";

            if (  true && typeof module.exports === "object" ) {

                // For CommonJS and CommonJS-like environments where a proper `window`
                // is present, execute the factory and get jQuery.
                // For environments that do not have a `window` with a `document`
                // (such as Node.js), expose a factory as module.exports.
                // This accentuates the need for the creation of a real `window`.
                // e.g. var jQuery = require("jquery")(window);
                // See ticket #14549 for more info.
                module.exports = global.document ?
                    factory( global, true ) :
                    function( w ) {
                        if ( !w.document ) {
                            throw new Error( "jQuery requires a window with a document" );
                        }
                        return factory( w );
                    };
            } else {
                factory( global );
            }

// Pass this if window is not defined yet
        } )( typeof window !== "undefined" ? window : this, function( window, noGlobal ) {

// Edge <= 12 - 13+, Firefox <=18 - 45+, IE 10 - 11, Safari 5.1 - 9+, iOS 6 - 9.1
// throw exceptions when non-strict code (e.g., ASP.NET 4.5) accesses strict mode
// arguments.callee.caller (trac-13335). But as of jQuery 3.0 (2016), strict mode should be common
// enough that all such attempts are guarded in a try block.
            "use strict";

            var arr = [];

            var getProto = Object.getPrototypeOf;

            var slice = arr.slice;

            var flat = arr.flat ? function( array ) {
                return arr.flat.call( array );
            } : function( array ) {
                return arr.concat.apply( [], array );
            };


            var push = arr.push;

            var indexOf = arr.indexOf;

            var class2type = {};

            var toString = class2type.toString;

            var hasOwn = class2type.hasOwnProperty;

            var fnToString = hasOwn.toString;

            var ObjectFunctionString = fnToString.call( Object );

            var support = {};

            var isFunction = function isFunction( obj ) {

                // Support: Chrome <=57, Firefox <=52
                // In some browsers, typeof returns "function" for HTML <object> elements
                // (i.e., `typeof document.createElement( "object" ) === "function"`).
                // We don't want to classify *any* DOM node as a function.
                return typeof obj === "function" && typeof obj.nodeType !== "number";
            };


            var isWindow = function isWindow( obj ) {
                return obj != null && obj === obj.window;
            };


            var document = window.document;



            var preservedScriptAttributes = {
                type: true,
                src: true,
                nonce: true,
                noModule: true
            };

            function DOMEval( code, node, doc ) {
                doc = doc || document;

                var i, val,
                    script = doc.createElement( "script" );

                script.text = code;
                if ( node ) {
                    for ( i in preservedScriptAttributes ) {

                        // Support: Firefox 64+, Edge 18+
                        // Some browsers don't support the "nonce" property on scripts.
                        // On the other hand, just using `getAttribute` is not enough as
                        // the `nonce` attribute is reset to an empty string whenever it
                        // becomes browsing-context connected.
                        // See https://github.com/whatwg/html/issues/2369
                        // See https://html.spec.whatwg.org/#nonce-attributes
                        // The `node.getAttribute` check was added for the sake of
                        // `jQuery.globalEval` so that it can fake a nonce-containing node
                        // via an object.
                        val = node[ i ] || node.getAttribute && node.getAttribute( i );
                        if ( val ) {
                            script.setAttribute( i, val );
                        }
                    }
                }
                doc.head.appendChild( script ).parentNode.removeChild( script );
            }


            function toType( obj ) {
                if ( obj == null ) {
                    return obj + "";
                }

                // Support: Android <=2.3 only (functionish RegExp)
                return typeof obj === "object" || typeof obj === "function" ?
                    class2type[ toString.call( obj ) ] || "object" :
                    typeof obj;
            }
            /* global Symbol */
// Defining this global in .eslintrc.json would create a danger of using the global
// unguarded in another place, it seems safer to define global only for this module



            var
                version = "3.5.1",

                // Define a local copy of jQuery
                jQuery = function( selector, context ) {

                    // The jQuery object is actually just the init constructor 'enhanced'
                    // Need init if jQuery is called (just allow error to be thrown if not included)
                    return new jQuery.fn.init( selector, context );
                };

            jQuery.fn = jQuery.prototype = {

                // The current version of jQuery being used
                jquery: version,

                constructor: jQuery,

                // The default length of a jQuery object is 0
                length: 0,

                toArray: function() {
                    return slice.call( this );
                },

                // Get the Nth element in the matched element set OR
                // Get the whole matched element set as a clean array
                get: function( num ) {

                    // Return all the elements in a clean array
                    if ( num == null ) {
                        return slice.call( this );
                    }

                    // Return just the one element from the set
                    return num < 0 ? this[ num + this.length ] : this[ num ];
                },

                // Take an array of elements and push it onto the stack
                // (returning the new matched element set)
                pushStack: function( elems ) {

                    // Build a new jQuery matched element set
                    var ret = jQuery.merge( this.constructor(), elems );

                    // Add the old object onto the stack (as a reference)
                    ret.prevObject = this;

                    // Return the newly-formed element set
                    return ret;
                },

                // Execute a callback for every element in the matched set.
                each: function( callback ) {
                    return jQuery.each( this, callback );
                },

                map: function( callback ) {
                    return this.pushStack( jQuery.map( this, function( elem, i ) {
                        return callback.call( elem, i, elem );
                    } ) );
                },

                slice: function() {
                    return this.pushStack( slice.apply( this, arguments ) );
                },

                first: function() {
                    return this.eq( 0 );
                },

                last: function() {
                    return this.eq( -1 );
                },

                even: function() {
                    return this.pushStack( jQuery.grep( this, function( _elem, i ) {
                        return ( i + 1 ) % 2;
                    } ) );
                },

                odd: function() {
                    return this.pushStack( jQuery.grep( this, function( _elem, i ) {
                        return i % 2;
                    } ) );
                },

                eq: function( i ) {
                    var len = this.length,
                        j = +i + ( i < 0 ? len : 0 );
                    return this.pushStack( j >= 0 && j < len ? [ this[ j ] ] : [] );
                },

                end: function() {
                    return this.prevObject || this.constructor();
                },

                // For internal use only.
                // Behaves like an Array's method, not like a jQuery method.
                push: push,
                sort: arr.sort,
                splice: arr.splice
            };

            jQuery.extend = jQuery.fn.extend = function() {
                var options, name, src, copy, copyIsArray, clone,
                    target = arguments[ 0 ] || {},
                    i = 1,
                    length = arguments.length,
                    deep = false;

                // Handle a deep copy situation
                if ( typeof target === "boolean" ) {
                    deep = target;

                    // Skip the boolean and the target
                    target = arguments[ i ] || {};
                    i++;
                }

                // Handle case when target is a string or something (possible in deep copy)
                if ( typeof target !== "object" && !isFunction( target ) ) {
                    target = {};
                }

                // Extend jQuery itself if only one argument is passed
                if ( i === length ) {
                    target = this;
                    i--;
                }

                for ( ; i < length; i++ ) {

                    // Only deal with non-null/undefined values
                    if ( ( options = arguments[ i ] ) != null ) {

                        // Extend the base object
                        for ( name in options ) {
                            copy = options[ name ];

                            // Prevent Object.prototype pollution
                            // Prevent never-ending loop
                            if ( name === "__proto__" || target === copy ) {
                                continue;
                            }

                            // Recurse if we're merging plain objects or arrays
                            if ( deep && copy && ( jQuery.isPlainObject( copy ) ||
                                ( copyIsArray = Array.isArray( copy ) ) ) ) {
                                src = target[ name ];

                                // Ensure proper type for the source value
                                if ( copyIsArray && !Array.isArray( src ) ) {
                                    clone = [];
                                } else if ( !copyIsArray && !jQuery.isPlainObject( src ) ) {
                                    clone = {};
                                } else {
                                    clone = src;
                                }
                                copyIsArray = false;

                                // Never move original objects, clone them
                                target[ name ] = jQuery.extend( deep, clone, copy );

                                // Don't bring in undefined values
                            } else if ( copy !== undefined ) {
                                target[ name ] = copy;
                            }
                        }
                    }
                }

                // Return the modified object
                return target;
            };

            jQuery.extend( {

                // Unique for each copy of jQuery on the page
                expando: "jQuery" + ( version + Math.random() ).replace( /\D/g, "" ),

                // Assume jQuery is ready without the ready module
                isReady: true,

                error: function( msg ) {
                    throw new Error( msg );
                },

                noop: function() {},

                isPlainObject: function( obj ) {
                    var proto, Ctor;

                    // Detect obvious negatives
                    // Use toString instead of jQuery.type to catch host objects
                    if ( !obj || toString.call( obj ) !== "[object Object]" ) {
                        return false;
                    }

                    proto = getProto( obj );

                    // Objects with no prototype (e.g., `Object.create( null )`) are plain
                    if ( !proto ) {
                        return true;
                    }

                    // Objects with prototype are plain iff they were constructed by a global Object function
                    Ctor = hasOwn.call( proto, "constructor" ) && proto.constructor;
                    return typeof Ctor === "function" && fnToString.call( Ctor ) === ObjectFunctionString;
                },

                isEmptyObject: function( obj ) {
                    var name;

                    for ( name in obj ) {
                        return false;
                    }
                    return true;
                },

                // Evaluates a script in a provided context; falls back to the global one
                // if not specified.
                globalEval: function( code, options, doc ) {
                    DOMEval( code, { nonce: options && options.nonce }, doc );
                },

                each: function( obj, callback ) {
                    var length, i = 0;

                    if ( isArrayLike( obj ) ) {
                        length = obj.length;
                        for ( ; i < length; i++ ) {
                            if ( callback.call( obj[ i ], i, obj[ i ] ) === false ) {
                                break;
                            }
                        }
                    } else {
                        for ( i in obj ) {
                            if ( callback.call( obj[ i ], i, obj[ i ] ) === false ) {
                                break;
                            }
                        }
                    }

                    return obj;
                },

                // results is for internal usage only
                makeArray: function( arr, results ) {
                    var ret = results || [];

                    if ( arr != null ) {
                        if ( isArrayLike( Object( arr ) ) ) {
                            jQuery.merge( ret,
                                typeof arr === "string" ?
                                    [ arr ] : arr
                            );
                        } else {
                            push.call( ret, arr );
                        }
                    }

                    return ret;
                },

                inArray: function( elem, arr, i ) {
                    return arr == null ? -1 : indexOf.call( arr, elem, i );
                },

                // Support: Android <=4.0 only, PhantomJS 1 only
                // push.apply(_, arraylike) throws on ancient WebKit
                merge: function( first, second ) {
                    var len = +second.length,
                        j = 0,
                        i = first.length;

                    for ( ; j < len; j++ ) {
                        first[ i++ ] = second[ j ];
                    }

                    first.length = i;

                    return first;
                },

                grep: function( elems, callback, invert ) {
                    var callbackInverse,
                        matches = [],
                        i = 0,
                        length = elems.length,
                        callbackExpect = !invert;

                    // Go through the array, only saving the items
                    // that pass the validator function
                    for ( ; i < length; i++ ) {
                        callbackInverse = !callback( elems[ i ], i );
                        if ( callbackInverse !== callbackExpect ) {
                            matches.push( elems[ i ] );
                        }
                    }

                    return matches;
                },

                // arg is for internal usage only
                map: function( elems, callback, arg ) {
                    var length, value,
                        i = 0,
                        ret = [];

                    // Go through the array, translating each of the items to their new values
                    if ( isArrayLike( elems ) ) {
                        length = elems.length;
                        for ( ; i < length; i++ ) {
                            value = callback( elems[ i ], i, arg );

                            if ( value != null ) {
                                ret.push( value );
                            }
                        }

                        // Go through every key on the object,
                    } else {
                        for ( i in elems ) {
                            value = callback( elems[ i ], i, arg );

                            if ( value != null ) {
                                ret.push( value );
                            }
                        }
                    }

                    // Flatten any nested arrays
                    return flat( ret );
                },

                // A global GUID counter for objects
                guid: 1,

                // jQuery.support is not used in Core but other projects attach their
                // properties to it so it needs to exist.
                support: support
            } );

            if ( typeof Symbol === "function" ) {
                jQuery.fn[ Symbol.iterator ] = arr[ Symbol.iterator ];
            }

// Populate the class2type map
            jQuery.each( "Boolean Number String Function Array Date RegExp Object Error Symbol".split( " " ),
                function( _i, name ) {
                    class2type[ "[object " + name + "]" ] = name.toLowerCase();
                } );

            function isArrayLike( obj ) {

                // Support: real iOS 8.2 only (not reproducible in simulator)
                // `in` check used to prevent JIT error (gh-2145)
                // hasOwn isn't used here due to false negatives
                // regarding Nodelist length in IE
                var length = !!obj && "length" in obj && obj.length,
                    type = toType( obj );

                if ( isFunction( obj ) || isWindow( obj ) ) {
                    return false;
                }

                return type === "array" || length === 0 ||
                    typeof length === "number" && length > 0 && ( length - 1 ) in obj;
            }
            var Sizzle =
                /*!
 * Sizzle CSS Selector Engine v2.3.5
 * https://sizzlejs.com/
 *
 * Copyright JS Foundation and other contributors
 * Released under the MIT license
 * https://js.foundation/
 *
 * Date: 2020-03-14
 */
                ( function( window ) {
                    var i,
                        support,
                        Expr,
                        getText,
                        isXML,
                        tokenize,
                        compile,
                        select,
                        outermostContext,
                        sortInput,
                        hasDuplicate,

                        // Local document vars
                        setDocument,
                        document,
                        docElem,
                        documentIsHTML,
                        rbuggyQSA,
                        rbuggyMatches,
                        matches,
                        contains,

                        // Instance-specific data
                        expando = "sizzle" + 1 * new Date(),
                        preferredDoc = window.document,
                        dirruns = 0,
                        done = 0,
                        classCache = createCache(),
                        tokenCache = createCache(),
                        compilerCache = createCache(),
                        nonnativeSelectorCache = createCache(),
                        sortOrder = function( a, b ) {
                            if ( a === b ) {
                                hasDuplicate = true;
                            }
                            return 0;
                        },

                        // Instance methods
                        hasOwn = ( {} ).hasOwnProperty,
                        arr = [],
                        pop = arr.pop,
                        pushNative = arr.push,
                        push = arr.push,
                        slice = arr.slice,

                        // Use a stripped-down indexOf as it's faster than native
                        // https://jsperf.com/thor-indexof-vs-for/5
                        indexOf = function( list, elem ) {
                            var i = 0,
                                len = list.length;
                            for ( ; i < len; i++ ) {
                                if ( list[ i ] === elem ) {
                                    return i;
                                }
                            }
                            return -1;
                        },

                        booleans = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|" +
                            "ismap|loop|multiple|open|readonly|required|scoped",

                        // Regular expressions

                        // http://www.w3.org/TR/css3-selectors/#whitespace
                        whitespace = "[\\x20\\t\\r\\n\\f]",

                        // https://www.w3.org/TR/css-syntax-3/#ident-token-diagram
                        identifier = "(?:\\\\[\\da-fA-F]{1,6}" + whitespace +
                            "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",

                        // Attribute selectors: http://www.w3.org/TR/selectors/#attribute-selectors
                        attributes = "\\[" + whitespace + "*(" + identifier + ")(?:" + whitespace +

                            // Operator (capture 2)
                            "*([*^$|!~]?=)" + whitespace +

                            // "Attribute values must be CSS identifiers [capture 5]
                            // or strings [capture 3 or capture 4]"
                            "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + identifier + "))|)" +
                            whitespace + "*\\]",

                        pseudos = ":(" + identifier + ")(?:\\((" +

                            // To reduce the number of selectors needing tokenize in the preFilter, prefer arguments:
                            // 1. quoted (capture 3; capture 4 or capture 5)
                            "('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" +

                            // 2. simple (capture 6)
                            "((?:\\\\.|[^\\\\()[\\]]|" + attributes + ")*)|" +

                            // 3. anything else (capture 2)
                            ".*" +
                            ")\\)|)",

                        // Leading and non-escaped trailing whitespace, capturing some non-whitespace characters preceding the latter
                        rwhitespace = new RegExp( whitespace + "+", "g" ),
                        rtrim = new RegExp( "^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" +
                            whitespace + "+$", "g" ),

                        rcomma = new RegExp( "^" + whitespace + "*," + whitespace + "*" ),
                        rcombinators = new RegExp( "^" + whitespace + "*([>+~]|" + whitespace + ")" + whitespace +
                            "*" ),
                        rdescend = new RegExp( whitespace + "|>" ),

                        rpseudo = new RegExp( pseudos ),
                        ridentifier = new RegExp( "^" + identifier + "$" ),

                        matchExpr = {
                            "ID": new RegExp( "^#(" + identifier + ")" ),
                            "CLASS": new RegExp( "^\\.(" + identifier + ")" ),
                            "TAG": new RegExp( "^(" + identifier + "|[*])" ),
                            "ATTR": new RegExp( "^" + attributes ),
                            "PSEUDO": new RegExp( "^" + pseudos ),
                            "CHILD": new RegExp( "^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" +
                                whitespace + "*(even|odd|(([+-]|)(\\d*)n|)" + whitespace + "*(?:([+-]|)" +
                                whitespace + "*(\\d+)|))" + whitespace + "*\\)|)", "i" ),
                            "bool": new RegExp( "^(?:" + booleans + ")$", "i" ),

                            // For use in libraries implementing .is()
                            // We use this for POS matching in `select`
                            "needsContext": new RegExp( "^" + whitespace +
                                "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + whitespace +
                                "*((?:-\\d)?\\d*)" + whitespace + "*\\)|)(?=[^-]|$)", "i" )
                        },

                        rhtml = /HTML$/i,
                        rinputs = /^(?:input|select|textarea|button)$/i,
                        rheader = /^h\d$/i,

                        rnative = /^[^{]+\{\s*\[native \w/,

                        // Easily-parseable/retrievable ID or TAG or CLASS selectors
                        rquickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,

                        rsibling = /[+~]/,

                        // CSS escapes
                        // http://www.w3.org/TR/CSS21/syndata.html#escaped-characters
                        runescape = new RegExp( "\\\\[\\da-fA-F]{1,6}" + whitespace + "?|\\\\([^\\r\\n\\f])", "g" ),
                        funescape = function( escape, nonHex ) {
                            var high = "0x" + escape.slice( 1 ) - 0x10000;

                            return nonHex ?

                                // Strip the backslash prefix from a non-hex escape sequence
                                nonHex :

                                // Replace a hexadecimal escape sequence with the encoded Unicode code point
                                // Support: IE <=11+
                                // For values outside the Basic Multilingual Plane (BMP), manually construct a
                                // surrogate pair
                                high < 0 ?
                                    String.fromCharCode( high + 0x10000 ) :
                                    String.fromCharCode( high >> 10 | 0xD800, high & 0x3FF | 0xDC00 );
                        },

                        // CSS string/identifier serialization
                        // https://drafts.csswg.org/cssom/#common-serializing-idioms
                        rcssescape = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
                        fcssescape = function( ch, asCodePoint ) {
                            if ( asCodePoint ) {

                                // U+0000 NULL becomes U+FFFD REPLACEMENT CHARACTER
                                if ( ch === "\0" ) {
                                    return "\uFFFD";
                                }

                                // Control characters and (dependent upon position) numbers get escaped as code points
                                return ch.slice( 0, -1 ) + "\\" +
                                    ch.charCodeAt( ch.length - 1 ).toString( 16 ) + " ";
                            }

                            // Other potentially-special ASCII characters get backslash-escaped
                            return "\\" + ch;
                        },

                        // Used for iframes
                        // See setDocument()
                        // Removing the function wrapper causes a "Permission Denied"
                        // error in IE
                        unloadHandler = function() {
                            setDocument();
                        },

                        inDisabledFieldset = addCombinator(
                            function( elem ) {
                                return elem.disabled === true && elem.nodeName.toLowerCase() === "fieldset";
                            },
                            { dir: "parentNode", next: "legend" }
                        );

// Optimize for push.apply( _, NodeList )
                    try {
                        push.apply(
                            ( arr = slice.call( preferredDoc.childNodes ) ),
                            preferredDoc.childNodes
                        );

                        // Support: Android<4.0
                        // Detect silently failing push.apply
                        // eslint-disable-next-line no-unused-expressions
                        arr[ preferredDoc.childNodes.length ].nodeType;
                    } catch ( e ) {
                        push = { apply: arr.length ?

                                // Leverage slice if possible
                                function( target, els ) {
                                    pushNative.apply( target, slice.call( els ) );
                                } :

                                // Support: IE<9
                                // Otherwise append directly
                                function( target, els ) {
                                    var j = target.length,
                                        i = 0;

                                    // Can't trust NodeList.length
                                    while ( ( target[ j++ ] = els[ i++ ] ) ) {}
                                    target.length = j - 1;
                                }
                        };
                    }

                    function Sizzle( selector, context, results, seed ) {
                        var m, i, elem, nid, match, groups, newSelector,
                            newContext = context && context.ownerDocument,

                            // nodeType defaults to 9, since context defaults to document
                            nodeType = context ? context.nodeType : 9;

                        results = results || [];

                        // Return early from calls with invalid selector or context
                        if ( typeof selector !== "string" || !selector ||
                            nodeType !== 1 && nodeType !== 9 && nodeType !== 11 ) {

                            return results;
                        }

                        // Try to shortcut find operations (as opposed to filters) in HTML documents
                        if ( !seed ) {
                            setDocument( context );
                            context = context || document;

                            if ( documentIsHTML ) {

                                // If the selector is sufficiently simple, try using a "get*By*" DOM method
                                // (excepting DocumentFragment context, where the methods don't exist)
                                if ( nodeType !== 11 && ( match = rquickExpr.exec( selector ) ) ) {

                                    // ID selector
                                    if ( ( m = match[ 1 ] ) ) {

                                        // Document context
                                        if ( nodeType === 9 ) {
                                            if ( ( elem = context.getElementById( m ) ) ) {

                                                // Support: IE, Opera, Webkit
                                                // TODO: identify versions
                                                // getElementById can match elements by name instead of ID
                                                if ( elem.id === m ) {
                                                    results.push( elem );
                                                    return results;
                                                }
                                            } else {
                                                return results;
                                            }

                                            // Element context
                                        } else {

                                            // Support: IE, Opera, Webkit
                                            // TODO: identify versions
                                            // getElementById can match elements by name instead of ID
                                            if ( newContext && ( elem = newContext.getElementById( m ) ) &&
                                                contains( context, elem ) &&
                                                elem.id === m ) {

                                                results.push( elem );
                                                return results;
                                            }
                                        }

                                        // Type selector
                                    } else if ( match[ 2 ] ) {
                                        push.apply( results, context.getElementsByTagName( selector ) );
                                        return results;

                                        // Class selector
                                    } else if ( ( m = match[ 3 ] ) && support.getElementsByClassName &&
                                        context.getElementsByClassName ) {

                                        push.apply( results, context.getElementsByClassName( m ) );
                                        return results;
                                    }
                                }

                                // Take advantage of querySelectorAll
                                if ( support.qsa &&
                                    !nonnativeSelectorCache[ selector + " " ] &&
                                    ( !rbuggyQSA || !rbuggyQSA.test( selector ) ) &&

                                    // Support: IE 8 only
                                    // Exclude object elements
                                    ( nodeType !== 1 || context.nodeName.toLowerCase() !== "object" ) ) {

                                    newSelector = selector;
                                    newContext = context;

                                    // qSA considers elements outside a scoping root when evaluating child or
                                    // descendant combinators, which is not what we want.
                                    // In such cases, we work around the behavior by prefixing every selector in the
                                    // list with an ID selector referencing the scope context.
                                    // The technique has to be used as well when a leading combinator is used
                                    // as such selectors are not recognized by querySelectorAll.
                                    // Thanks to Andrew Dupont for this technique.
                                    if ( nodeType === 1 &&
                                        ( rdescend.test( selector ) || rcombinators.test( selector ) ) ) {

                                        // Expand context for sibling selectors
                                        newContext = rsibling.test( selector ) && testContext( context.parentNode ) ||
                                            context;

                                        // We can use :scope instead of the ID hack if the browser
                                        // supports it & if we're not changing the context.
                                        if ( newContext !== context || !support.scope ) {

                                            // Capture the context ID, setting it first if necessary
                                            if ( ( nid = context.getAttribute( "id" ) ) ) {
                                                nid = nid.replace( rcssescape, fcssescape );
                                            } else {
                                                context.setAttribute( "id", ( nid = expando ) );
                                            }
                                        }

                                        // Prefix every selector in the list
                                        groups = tokenize( selector );
                                        i = groups.length;
                                        while ( i-- ) {
                                            groups[ i ] = ( nid ? "#" + nid : ":scope" ) + " " +
                                                toSelector( groups[ i ] );
                                        }
                                        newSelector = groups.join( "," );
                                    }

                                    try {
                                        push.apply( results,
                                            newContext.querySelectorAll( newSelector )
                                        );
                                        return results;
                                    } catch ( qsaError ) {
                                        nonnativeSelectorCache( selector, true );
                                    } finally {
                                        if ( nid === expando ) {
                                            context.removeAttribute( "id" );
                                        }
                                    }
                                }
                            }
                        }

                        // All others
                        return select( selector.replace( rtrim, "$1" ), context, results, seed );
                    }

                    /**
                     * Create key-value caches of limited size
                     * @returns {function(string, object)} Returns the Object data after storing it on itself with
                     *	property name the (space-suffixed) string and (if the cache is larger than Expr.cacheLength)
                     *	deleting the oldest entry
                     */
                    function createCache() {
                        var keys = [];

                        function cache( key, value ) {

                            // Use (key + " ") to avoid collision with native prototype properties (see Issue #157)
                            if ( keys.push( key + " " ) > Expr.cacheLength ) {

                                // Only keep the most recent entries
                                delete cache[ keys.shift() ];
                            }
                            return ( cache[ key + " " ] = value );
                        }
                        return cache;
                    }

                    /**
                     * Mark a function for special use by Sizzle
                     * @param {Function} fn The function to mark
                     */
                    function markFunction( fn ) {
                        fn[ expando ] = true;
                        return fn;
                    }

                    /**
                     * Support testing using an element
                     * @param {Function} fn Passed the created element and returns a boolean result
                     */
                    function assert( fn ) {
                        var el = document.createElement( "fieldset" );

                        try {
                            return !!fn( el );
                        } catch ( e ) {
                            return false;
                        } finally {

                            // Remove from its parent by default
                            if ( el.parentNode ) {
                                el.parentNode.removeChild( el );
                            }

                            // release memory in IE
                            el = null;
                        }
                    }

                    /**
                     * Adds the same handler for all of the specified attrs
                     * @param {String} attrs Pipe-separated list of attributes
                     * @param {Function} handler The method that will be applied
                     */
                    function addHandle( attrs, handler ) {
                        var arr = attrs.split( "|" ),
                            i = arr.length;

                        while ( i-- ) {
                            Expr.attrHandle[ arr[ i ] ] = handler;
                        }
                    }

                    /**
                     * Checks document order of two siblings
                     * @param {Element} a
                     * @param {Element} b
                     * @returns {Number} Returns less than 0 if a precedes b, greater than 0 if a follows b
                     */
                    function siblingCheck( a, b ) {
                        var cur = b && a,
                            diff = cur && a.nodeType === 1 && b.nodeType === 1 &&
                                a.sourceIndex - b.sourceIndex;

                        // Use IE sourceIndex if available on both nodes
                        if ( diff ) {
                            return diff;
                        }

                        // Check if b follows a
                        if ( cur ) {
                            while ( ( cur = cur.nextSibling ) ) {
                                if ( cur === b ) {
                                    return -1;
                                }
                            }
                        }

                        return a ? 1 : -1;
                    }

                    /**
                     * Returns a function to use in pseudos for input types
                     * @param {String} type
                     */
                    function createInputPseudo( type ) {
                        return function( elem ) {
                            var name = elem.nodeName.toLowerCase();
                            return name === "input" && elem.type === type;
                        };
                    }

                    /**
                     * Returns a function to use in pseudos for buttons
                     * @param {String} type
                     */
                    function createButtonPseudo( type ) {
                        return function( elem ) {
                            var name = elem.nodeName.toLowerCase();
                            return ( name === "input" || name === "button" ) && elem.type === type;
                        };
                    }

                    /**
                     * Returns a function to use in pseudos for :enabled/:disabled
                     * @param {Boolean} disabled true for :disabled; false for :enabled
                     */
                    function createDisabledPseudo( disabled ) {

                        // Known :disabled false positives: fieldset[disabled] > legend:nth-of-type(n+2) :can-disable
                        return function( elem ) {

                            // Only certain elements can match :enabled or :disabled
                            // https://html.spec.whatwg.org/multipage/scripting.html#selector-enabled
                            // https://html.spec.whatwg.org/multipage/scripting.html#selector-disabled
                            if ( "form" in elem ) {

                                // Check for inherited disabledness on relevant non-disabled elements:
                                // * listed form-associated elements in a disabled fieldset
                                //   https://html.spec.whatwg.org/multipage/forms.html#category-listed
                                //   https://html.spec.whatwg.org/multipage/forms.html#concept-fe-disabled
                                // * option elements in a disabled optgroup
                                //   https://html.spec.whatwg.org/multipage/forms.html#concept-option-disabled
                                // All such elements have a "form" property.
                                if ( elem.parentNode && elem.disabled === false ) {

                                    // Option elements defer to a parent optgroup if present
                                    if ( "label" in elem ) {
                                        if ( "label" in elem.parentNode ) {
                                            return elem.parentNode.disabled === disabled;
                                        } else {
                                            return elem.disabled === disabled;
                                        }
                                    }

                                    // Support: IE 6 - 11
                                    // Use the isDisabled shortcut property to check for disabled fieldset ancestors
                                    return elem.isDisabled === disabled ||

                                        // Where there is no isDisabled, check manually
                                        /* jshint -W018 */
                                        elem.isDisabled !== !disabled &&
                                        inDisabledFieldset( elem ) === disabled;
                                }

                                return elem.disabled === disabled;

                                // Try to winnow out elements that can't be disabled before trusting the disabled property.
                                // Some victims get caught in our net (label, legend, menu, track), but it shouldn't
                                // even exist on them, let alone have a boolean value.
                            } else if ( "label" in elem ) {
                                return elem.disabled === disabled;
                            }

                            // Remaining elements are neither :enabled nor :disabled
                            return false;
                        };
                    }

                    /**
                     * Returns a function to use in pseudos for positionals
                     * @param {Function} fn
                     */
                    function createPositionalPseudo( fn ) {
                        return markFunction( function( argument ) {
                            argument = +argument;
                            return markFunction( function( seed, matches ) {
                                var j,
                                    matchIndexes = fn( [], seed.length, argument ),
                                    i = matchIndexes.length;

                                // Match elements found at the specified indexes
                                while ( i-- ) {
                                    if ( seed[ ( j = matchIndexes[ i ] ) ] ) {
                                        seed[ j ] = !( matches[ j ] = seed[ j ] );
                                    }
                                }
                            } );
                        } );
                    }

                    /**
                     * Checks a node for validity as a Sizzle context
                     * @param {Element|Object=} context
                     * @returns {Element|Object|Boolean} The input node if acceptable, otherwise a falsy value
                     */
                    function testContext( context ) {
                        return context && typeof context.getElementsByTagName !== "undefined" && context;
                    }

// Expose support vars for convenience
                    support = Sizzle.support = {};

                    /**
                     * Detects XML nodes
                     * @param {Element|Object} elem An element or a document
                     * @returns {Boolean} True iff elem is a non-HTML XML node
                     */
                    isXML = Sizzle.isXML = function( elem ) {
                        var namespace = elem.namespaceURI,
                            docElem = ( elem.ownerDocument || elem ).documentElement;

                        // Support: IE <=8
                        // Assume HTML when documentElement doesn't yet exist, such as inside loading iframes
                        // https://bugs.jquery.com/ticket/4833
                        return !rhtml.test( namespace || docElem && docElem.nodeName || "HTML" );
                    };

                    /**
                     * Sets document-related variables once based on the current document
                     * @param {Element|Object} [doc] An element or document object to use to set the document
                     * @returns {Object} Returns the current document
                     */
                    setDocument = Sizzle.setDocument = function( node ) {
                        var hasCompare, subWindow,
                            doc = node ? node.ownerDocument || node : preferredDoc;

                        // Return early if doc is invalid or already selected
                        // Support: IE 11+, Edge 17 - 18+
                        // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                        // two documents; shallow comparisons work.
                        // eslint-disable-next-line eqeqeq
                        if ( doc == document || doc.nodeType !== 9 || !doc.documentElement ) {
                            return document;
                        }

                        // Update global variables
                        document = doc;
                        docElem = document.documentElement;
                        documentIsHTML = !isXML( document );

                        // Support: IE 9 - 11+, Edge 12 - 18+
                        // Accessing iframe documents after unload throws "permission denied" errors (jQuery #13936)
                        // Support: IE 11+, Edge 17 - 18+
                        // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                        // two documents; shallow comparisons work.
                        // eslint-disable-next-line eqeqeq
                        if ( preferredDoc != document &&
                            ( subWindow = document.defaultView ) && subWindow.top !== subWindow ) {

                            // Support: IE 11, Edge
                            if ( subWindow.addEventListener ) {
                                subWindow.addEventListener( "unload", unloadHandler, false );

                                // Support: IE 9 - 10 only
                            } else if ( subWindow.attachEvent ) {
                                subWindow.attachEvent( "onunload", unloadHandler );
                            }
                        }

                        // Support: IE 8 - 11+, Edge 12 - 18+, Chrome <=16 - 25 only, Firefox <=3.6 - 31 only,
                        // Safari 4 - 5 only, Opera <=11.6 - 12.x only
                        // IE/Edge & older browsers don't support the :scope pseudo-class.
                        // Support: Safari 6.0 only
                        // Safari 6.0 supports :scope but it's an alias of :root there.
                        support.scope = assert( function( el ) {
                            docElem.appendChild( el ).appendChild( document.createElement( "div" ) );
                            return typeof el.querySelectorAll !== "undefined" &&
                                !el.querySelectorAll( ":scope fieldset div" ).length;
                        } );

                        /* Attributes
	---------------------------------------------------------------------- */

                        // Support: IE<8
                        // Verify that getAttribute really returns attributes and not properties
                        // (excepting IE8 booleans)
                        support.attributes = assert( function( el ) {
                            el.className = "i";
                            return !el.getAttribute( "className" );
                        } );

                        /* getElement(s)By*
	---------------------------------------------------------------------- */

                        // Check if getElementsByTagName("*") returns only elements
                        support.getElementsByTagName = assert( function( el ) {
                            el.appendChild( document.createComment( "" ) );
                            return !el.getElementsByTagName( "*" ).length;
                        } );

                        // Support: IE<9
                        support.getElementsByClassName = rnative.test( document.getElementsByClassName );

                        // Support: IE<10
                        // Check if getElementById returns elements by name
                        // The broken getElementById methods don't pick up programmatically-set names,
                        // so use a roundabout getElementsByName test
                        support.getById = assert( function( el ) {
                            docElem.appendChild( el ).id = expando;
                            return !document.getElementsByName || !document.getElementsByName( expando ).length;
                        } );

                        // ID filter and find
                        if ( support.getById ) {
                            Expr.filter[ "ID" ] = function( id ) {
                                var attrId = id.replace( runescape, funescape );
                                return function( elem ) {
                                    return elem.getAttribute( "id" ) === attrId;
                                };
                            };
                            Expr.find[ "ID" ] = function( id, context ) {
                                if ( typeof context.getElementById !== "undefined" && documentIsHTML ) {
                                    var elem = context.getElementById( id );
                                    return elem ? [ elem ] : [];
                                }
                            };
                        } else {
                            Expr.filter[ "ID" ] =  function( id ) {
                                var attrId = id.replace( runescape, funescape );
                                return function( elem ) {
                                    var node = typeof elem.getAttributeNode !== "undefined" &&
                                        elem.getAttributeNode( "id" );
                                    return node && node.value === attrId;
                                };
                            };

                            // Support: IE 6 - 7 only
                            // getElementById is not reliable as a find shortcut
                            Expr.find[ "ID" ] = function( id, context ) {
                                if ( typeof context.getElementById !== "undefined" && documentIsHTML ) {
                                    var node, i, elems,
                                        elem = context.getElementById( id );

                                    if ( elem ) {

                                        // Verify the id attribute
                                        node = elem.getAttributeNode( "id" );
                                        if ( node && node.value === id ) {
                                            return [ elem ];
                                        }

                                        // Fall back on getElementsByName
                                        elems = context.getElementsByName( id );
                                        i = 0;
                                        while ( ( elem = elems[ i++ ] ) ) {
                                            node = elem.getAttributeNode( "id" );
                                            if ( node && node.value === id ) {
                                                return [ elem ];
                                            }
                                        }
                                    }

                                    return [];
                                }
                            };
                        }

                        // Tag
                        Expr.find[ "TAG" ] = support.getElementsByTagName ?
                            function( tag, context ) {
                                if ( typeof context.getElementsByTagName !== "undefined" ) {
                                    return context.getElementsByTagName( tag );

                                    // DocumentFragment nodes don't have gEBTN
                                } else if ( support.qsa ) {
                                    return context.querySelectorAll( tag );
                                }
                            } :

                            function( tag, context ) {
                                var elem,
                                    tmp = [],
                                    i = 0,

                                    // By happy coincidence, a (broken) gEBTN appears on DocumentFragment nodes too
                                    results = context.getElementsByTagName( tag );

                                // Filter out possible comments
                                if ( tag === "*" ) {
                                    while ( ( elem = results[ i++ ] ) ) {
                                        if ( elem.nodeType === 1 ) {
                                            tmp.push( elem );
                                        }
                                    }

                                    return tmp;
                                }
                                return results;
                            };

                        // Class
                        Expr.find[ "CLASS" ] = support.getElementsByClassName && function( className, context ) {
                            if ( typeof context.getElementsByClassName !== "undefined" && documentIsHTML ) {
                                return context.getElementsByClassName( className );
                            }
                        };

                        /* QSA/matchesSelector
	---------------------------------------------------------------------- */

                        // QSA and matchesSelector support

                        // matchesSelector(:active) reports false when true (IE9/Opera 11.5)
                        rbuggyMatches = [];

                        // qSa(:focus) reports false when true (Chrome 21)
                        // We allow this because of a bug in IE8/9 that throws an error
                        // whenever `document.activeElement` is accessed on an iframe
                        // So, we allow :focus to pass through QSA all the time to avoid the IE error
                        // See https://bugs.jquery.com/ticket/13378
                        rbuggyQSA = [];

                        if ( ( support.qsa = rnative.test( document.querySelectorAll ) ) ) {

                            // Build QSA regex
                            // Regex strategy adopted from Diego Perini
                            assert( function( el ) {

                                var input;

                                // Select is set to empty string on purpose
                                // This is to test IE's treatment of not explicitly
                                // setting a boolean content attribute,
                                // since its presence should be enough
                                // https://bugs.jquery.com/ticket/12359
                                docElem.appendChild( el ).innerHTML = "<a id='" + expando + "'></a>" +
                                    "<select id='" + expando + "-\r\\' msallowcapture=''>" +
                                    "<option selected=''></option></select>";

                                // Support: IE8, Opera 11-12.16
                                // Nothing should be selected when empty strings follow ^= or $= or *=
                                // The test attribute must be unknown in Opera but "safe" for WinRT
                                // https://msdn.microsoft.com/en-us/library/ie/hh465388.aspx#attribute_section
                                if ( el.querySelectorAll( "[msallowcapture^='']" ).length ) {
                                    rbuggyQSA.push( "[*^$]=" + whitespace + "*(?:''|\"\")" );
                                }

                                // Support: IE8
                                // Boolean attributes and "value" are not treated correctly
                                if ( !el.querySelectorAll( "[selected]" ).length ) {
                                    rbuggyQSA.push( "\\[" + whitespace + "*(?:value|" + booleans + ")" );
                                }

                                // Support: Chrome<29, Android<4.4, Safari<7.0+, iOS<7.0+, PhantomJS<1.9.8+
                                if ( !el.querySelectorAll( "[id~=" + expando + "-]" ).length ) {
                                    rbuggyQSA.push( "~=" );
                                }

                                // Support: IE 11+, Edge 15 - 18+
                                // IE 11/Edge don't find elements on a `[name='']` query in some cases.
                                // Adding a temporary attribute to the document before the selection works
                                // around the issue.
                                // Interestingly, IE 10 & older don't seem to have the issue.
                                input = document.createElement( "input" );
                                input.setAttribute( "name", "" );
                                el.appendChild( input );
                                if ( !el.querySelectorAll( "[name='']" ).length ) {
                                    rbuggyQSA.push( "\\[" + whitespace + "*name" + whitespace + "*=" +
                                        whitespace + "*(?:''|\"\")" );
                                }

                                // Webkit/Opera - :checked should return selected option elements
                                // http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#checked
                                // IE8 throws error here and will not see later tests
                                if ( !el.querySelectorAll( ":checked" ).length ) {
                                    rbuggyQSA.push( ":checked" );
                                }

                                // Support: Safari 8+, iOS 8+
                                // https://bugs.webkit.org/show_bug.cgi?id=136851
                                // In-page `selector#id sibling-combinator selector` fails
                                if ( !el.querySelectorAll( "a#" + expando + "+*" ).length ) {
                                    rbuggyQSA.push( ".#.+[+~]" );
                                }

                                // Support: Firefox <=3.6 - 5 only
                                // Old Firefox doesn't throw on a badly-escaped identifier.
                                el.querySelectorAll( "\\\f" );
                                rbuggyQSA.push( "[\\r\\n\\f]" );
                            } );

                            assert( function( el ) {
                                el.innerHTML = "<a href='' disabled='disabled'></a>" +
                                    "<select disabled='disabled'><option/></select>";

                                // Support: Windows 8 Native Apps
                                // The type and name attributes are restricted during .innerHTML assignment
                                var input = document.createElement( "input" );
                                input.setAttribute( "type", "hidden" );
                                el.appendChild( input ).setAttribute( "name", "D" );

                                // Support: IE8
                                // Enforce case-sensitivity of name attribute
                                if ( el.querySelectorAll( "[name=d]" ).length ) {
                                    rbuggyQSA.push( "name" + whitespace + "*[*^$|!~]?=" );
                                }

                                // FF 3.5 - :enabled/:disabled and hidden elements (hidden elements are still enabled)
                                // IE8 throws error here and will not see later tests
                                if ( el.querySelectorAll( ":enabled" ).length !== 2 ) {
                                    rbuggyQSA.push( ":enabled", ":disabled" );
                                }

                                // Support: IE9-11+
                                // IE's :disabled selector does not pick up the children of disabled fieldsets
                                docElem.appendChild( el ).disabled = true;
                                if ( el.querySelectorAll( ":disabled" ).length !== 2 ) {
                                    rbuggyQSA.push( ":enabled", ":disabled" );
                                }

                                // Support: Opera 10 - 11 only
                                // Opera 10-11 does not throw on post-comma invalid pseudos
                                el.querySelectorAll( "*,:x" );
                                rbuggyQSA.push( ",.*:" );
                            } );
                        }

                        if ( ( support.matchesSelector = rnative.test( ( matches = docElem.matches ||
                            docElem.webkitMatchesSelector ||
                            docElem.mozMatchesSelector ||
                            docElem.oMatchesSelector ||
                            docElem.msMatchesSelector ) ) ) ) {

                            assert( function( el ) {

                                // Check to see if it's possible to do matchesSelector
                                // on a disconnected node (IE 9)
                                support.disconnectedMatch = matches.call( el, "*" );

                                // This should fail with an exception
                                // Gecko does not error, returns false instead
                                matches.call( el, "[s!='']:x" );
                                rbuggyMatches.push( "!=", pseudos );
                            } );
                        }

                        rbuggyQSA = rbuggyQSA.length && new RegExp( rbuggyQSA.join( "|" ) );
                        rbuggyMatches = rbuggyMatches.length && new RegExp( rbuggyMatches.join( "|" ) );

                        /* Contains
	---------------------------------------------------------------------- */
                        hasCompare = rnative.test( docElem.compareDocumentPosition );

                        // Element contains another
                        // Purposefully self-exclusive
                        // As in, an element does not contain itself
                        contains = hasCompare || rnative.test( docElem.contains ) ?
                            function( a, b ) {
                                var adown = a.nodeType === 9 ? a.documentElement : a,
                                    bup = b && b.parentNode;
                                return a === bup || !!( bup && bup.nodeType === 1 && (
                                    adown.contains ?
                                        adown.contains( bup ) :
                                        a.compareDocumentPosition && a.compareDocumentPosition( bup ) & 16
                                ) );
                            } :
                            function( a, b ) {
                                if ( b ) {
                                    while ( ( b = b.parentNode ) ) {
                                        if ( b === a ) {
                                            return true;
                                        }
                                    }
                                }
                                return false;
                            };

                        /* Sorting
	---------------------------------------------------------------------- */

                        // Document order sorting
                        sortOrder = hasCompare ?
                            function( a, b ) {

                                // Flag for duplicate removal
                                if ( a === b ) {
                                    hasDuplicate = true;
                                    return 0;
                                }

                                // Sort on method existence if only one input has compareDocumentPosition
                                var compare = !a.compareDocumentPosition - !b.compareDocumentPosition;
                                if ( compare ) {
                                    return compare;
                                }

                                // Calculate position if both inputs belong to the same document
                                // Support: IE 11+, Edge 17 - 18+
                                // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                // two documents; shallow comparisons work.
                                // eslint-disable-next-line eqeqeq
                                compare = ( a.ownerDocument || a ) == ( b.ownerDocument || b ) ?
                                    a.compareDocumentPosition( b ) :

                                    // Otherwise we know they are disconnected
                                    1;

                                // Disconnected nodes
                                if ( compare & 1 ||
                                    ( !support.sortDetached && b.compareDocumentPosition( a ) === compare ) ) {

                                    // Choose the first element that is related to our preferred document
                                    // Support: IE 11+, Edge 17 - 18+
                                    // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                    // two documents; shallow comparisons work.
                                    // eslint-disable-next-line eqeqeq
                                    if ( a == document || a.ownerDocument == preferredDoc &&
                                        contains( preferredDoc, a ) ) {
                                        return -1;
                                    }

                                    // Support: IE 11+, Edge 17 - 18+
                                    // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                    // two documents; shallow comparisons work.
                                    // eslint-disable-next-line eqeqeq
                                    if ( b == document || b.ownerDocument == preferredDoc &&
                                        contains( preferredDoc, b ) ) {
                                        return 1;
                                    }

                                    // Maintain original order
                                    return sortInput ?
                                        ( indexOf( sortInput, a ) - indexOf( sortInput, b ) ) :
                                        0;
                                }

                                return compare & 4 ? -1 : 1;
                            } :
                            function( a, b ) {

                                // Exit early if the nodes are identical
                                if ( a === b ) {
                                    hasDuplicate = true;
                                    return 0;
                                }

                                var cur,
                                    i = 0,
                                    aup = a.parentNode,
                                    bup = b.parentNode,
                                    ap = [ a ],
                                    bp = [ b ];

                                // Parentless nodes are either documents or disconnected
                                if ( !aup || !bup ) {

                                    // Support: IE 11+, Edge 17 - 18+
                                    // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                    // two documents; shallow comparisons work.
                                    /* eslint-disable eqeqeq */
                                    return a == document ? -1 :
                                        b == document ? 1 :
                                            /* eslint-enable eqeqeq */
                                            aup ? -1 :
                                                bup ? 1 :
                                                    sortInput ?
                                                        ( indexOf( sortInput, a ) - indexOf( sortInput, b ) ) :
                                                        0;

                                    // If the nodes are siblings, we can do a quick check
                                } else if ( aup === bup ) {
                                    return siblingCheck( a, b );
                                }

                                // Otherwise we need full lists of their ancestors for comparison
                                cur = a;
                                while ( ( cur = cur.parentNode ) ) {
                                    ap.unshift( cur );
                                }
                                cur = b;
                                while ( ( cur = cur.parentNode ) ) {
                                    bp.unshift( cur );
                                }

                                // Walk down the tree looking for a discrepancy
                                while ( ap[ i ] === bp[ i ] ) {
                                    i++;
                                }

                                return i ?

                                    // Do a sibling check if the nodes have a common ancestor
                                    siblingCheck( ap[ i ], bp[ i ] ) :

                                    // Otherwise nodes in our document sort first
                                    // Support: IE 11+, Edge 17 - 18+
                                    // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                    // two documents; shallow comparisons work.
                                    /* eslint-disable eqeqeq */
                                    ap[ i ] == preferredDoc ? -1 :
                                        bp[ i ] == preferredDoc ? 1 :
                                            /* eslint-enable eqeqeq */
                                            0;
                            };

                        return document;
                    };

                    Sizzle.matches = function( expr, elements ) {
                        return Sizzle( expr, null, null, elements );
                    };

                    Sizzle.matchesSelector = function( elem, expr ) {
                        setDocument( elem );

                        if ( support.matchesSelector && documentIsHTML &&
                            !nonnativeSelectorCache[ expr + " " ] &&
                            ( !rbuggyMatches || !rbuggyMatches.test( expr ) ) &&
                            ( !rbuggyQSA     || !rbuggyQSA.test( expr ) ) ) {

                            try {
                                var ret = matches.call( elem, expr );

                                // IE 9's matchesSelector returns false on disconnected nodes
                                if ( ret || support.disconnectedMatch ||

                                    // As well, disconnected nodes are said to be in a document
                                    // fragment in IE 9
                                    elem.document && elem.document.nodeType !== 11 ) {
                                    return ret;
                                }
                            } catch ( e ) {
                                nonnativeSelectorCache( expr, true );
                            }
                        }

                        return Sizzle( expr, document, null, [ elem ] ).length > 0;
                    };

                    Sizzle.contains = function( context, elem ) {

                        // Set document vars if needed
                        // Support: IE 11+, Edge 17 - 18+
                        // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                        // two documents; shallow comparisons work.
                        // eslint-disable-next-line eqeqeq
                        if ( ( context.ownerDocument || context ) != document ) {
                            setDocument( context );
                        }
                        return contains( context, elem );
                    };

                    Sizzle.attr = function( elem, name ) {

                        // Set document vars if needed
                        // Support: IE 11+, Edge 17 - 18+
                        // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                        // two documents; shallow comparisons work.
                        // eslint-disable-next-line eqeqeq
                        if ( ( elem.ownerDocument || elem ) != document ) {
                            setDocument( elem );
                        }

                        var fn = Expr.attrHandle[ name.toLowerCase() ],

                            // Don't get fooled by Object.prototype properties (jQuery #13807)
                            val = fn && hasOwn.call( Expr.attrHandle, name.toLowerCase() ) ?
                                fn( elem, name, !documentIsHTML ) :
                                undefined;

                        return val !== undefined ?
                            val :
                            support.attributes || !documentIsHTML ?
                                elem.getAttribute( name ) :
                                ( val = elem.getAttributeNode( name ) ) && val.specified ?
                                    val.value :
                                    null;
                    };

                    Sizzle.escape = function( sel ) {
                        return ( sel + "" ).replace( rcssescape, fcssescape );
                    };

                    Sizzle.error = function( msg ) {
                        throw new Error( "Syntax error, unrecognized expression: " + msg );
                    };

                    /**
                     * Document sorting and removing duplicates
                     * @param {ArrayLike} results
                     */
                    Sizzle.uniqueSort = function( results ) {
                        var elem,
                            duplicates = [],
                            j = 0,
                            i = 0;

                        // Unless we *know* we can detect duplicates, assume their presence
                        hasDuplicate = !support.detectDuplicates;
                        sortInput = !support.sortStable && results.slice( 0 );
                        results.sort( sortOrder );

                        if ( hasDuplicate ) {
                            while ( ( elem = results[ i++ ] ) ) {
                                if ( elem === results[ i ] ) {
                                    j = duplicates.push( i );
                                }
                            }
                            while ( j-- ) {
                                results.splice( duplicates[ j ], 1 );
                            }
                        }

                        // Clear input after sorting to release objects
                        // See https://github.com/jquery/sizzle/pull/225
                        sortInput = null;

                        return results;
                    };

                    /**
                     * Utility function for retrieving the text value of an array of DOM nodes
                     * @param {Array|Element} elem
                     */
                    getText = Sizzle.getText = function( elem ) {
                        var node,
                            ret = "",
                            i = 0,
                            nodeType = elem.nodeType;

                        if ( !nodeType ) {

                            // If no nodeType, this is expected to be an array
                            while ( ( node = elem[ i++ ] ) ) {

                                // Do not traverse comment nodes
                                ret += getText( node );
                            }
                        } else if ( nodeType === 1 || nodeType === 9 || nodeType === 11 ) {

                            // Use textContent for elements
                            // innerText usage removed for consistency of new lines (jQuery #11153)
                            if ( typeof elem.textContent === "string" ) {
                                return elem.textContent;
                            } else {

                                // Traverse its children
                                for ( elem = elem.firstChild; elem; elem = elem.nextSibling ) {
                                    ret += getText( elem );
                                }
                            }
                        } else if ( nodeType === 3 || nodeType === 4 ) {
                            return elem.nodeValue;
                        }

                        // Do not include comment or processing instruction nodes

                        return ret;
                    };

                    Expr = Sizzle.selectors = {

                        // Can be adjusted by the user
                        cacheLength: 50,

                        createPseudo: markFunction,

                        match: matchExpr,

                        attrHandle: {},

                        find: {},

                        relative: {
                            ">": { dir: "parentNode", first: true },
                            " ": { dir: "parentNode" },
                            "+": { dir: "previousSibling", first: true },
                            "~": { dir: "previousSibling" }
                        },

                        preFilter: {
                            "ATTR": function( match ) {
                                match[ 1 ] = match[ 1 ].replace( runescape, funescape );

                                // Move the given value to match[3] whether quoted or unquoted
                                match[ 3 ] = ( match[ 3 ] || match[ 4 ] ||
                                    match[ 5 ] || "" ).replace( runescape, funescape );

                                if ( match[ 2 ] === "~=" ) {
                                    match[ 3 ] = " " + match[ 3 ] + " ";
                                }

                                return match.slice( 0, 4 );
                            },

                            "CHILD": function( match ) {

                                /* matches from matchExpr["CHILD"]
				1 type (only|nth|...)
				2 what (child|of-type)
				3 argument (even|odd|\d*|\d*n([+-]\d+)?|...)
				4 xn-component of xn+y argument ([+-]?\d*n|)
				5 sign of xn-component
				6 x of xn-component
				7 sign of y-component
				8 y of y-component
			*/
                                match[ 1 ] = match[ 1 ].toLowerCase();

                                if ( match[ 1 ].slice( 0, 3 ) === "nth" ) {

                                    // nth-* requires argument
                                    if ( !match[ 3 ] ) {
                                        Sizzle.error( match[ 0 ] );
                                    }

                                    // numeric x and y parameters for Expr.filter.CHILD
                                    // remember that false/true cast respectively to 0/1
                                    match[ 4 ] = +( match[ 4 ] ?
                                        match[ 5 ] + ( match[ 6 ] || 1 ) :
                                        2 * ( match[ 3 ] === "even" || match[ 3 ] === "odd" ) );
                                    match[ 5 ] = +( ( match[ 7 ] + match[ 8 ] ) || match[ 3 ] === "odd" );

                                    // other types prohibit arguments
                                } else if ( match[ 3 ] ) {
                                    Sizzle.error( match[ 0 ] );
                                }

                                return match;
                            },

                            "PSEUDO": function( match ) {
                                var excess,
                                    unquoted = !match[ 6 ] && match[ 2 ];

                                if ( matchExpr[ "CHILD" ].test( match[ 0 ] ) ) {
                                    return null;
                                }

                                // Accept quoted arguments as-is
                                if ( match[ 3 ] ) {
                                    match[ 2 ] = match[ 4 ] || match[ 5 ] || "";

                                    // Strip excess characters from unquoted arguments
                                } else if ( unquoted && rpseudo.test( unquoted ) &&

                                    // Get excess from tokenize (recursively)
                                    ( excess = tokenize( unquoted, true ) ) &&

                                    // advance to the next closing parenthesis
                                    ( excess = unquoted.indexOf( ")", unquoted.length - excess ) - unquoted.length ) ) {

                                    // excess is a negative index
                                    match[ 0 ] = match[ 0 ].slice( 0, excess );
                                    match[ 2 ] = unquoted.slice( 0, excess );
                                }

                                // Return only captures needed by the pseudo filter method (type and argument)
                                return match.slice( 0, 3 );
                            }
                        },

                        filter: {

                            "TAG": function( nodeNameSelector ) {
                                var nodeName = nodeNameSelector.replace( runescape, funescape ).toLowerCase();
                                return nodeNameSelector === "*" ?
                                    function() {
                                        return true;
                                    } :
                                    function( elem ) {
                                        return elem.nodeName && elem.nodeName.toLowerCase() === nodeName;
                                    };
                            },

                            "CLASS": function( className ) {
                                var pattern = classCache[ className + " " ];

                                return pattern ||
                                    ( pattern = new RegExp( "(^|" + whitespace +
                                        ")" + className + "(" + whitespace + "|$)" ) ) && classCache(
                                        className, function( elem ) {
                                            return pattern.test(
                                                typeof elem.className === "string" && elem.className ||
                                                typeof elem.getAttribute !== "undefined" &&
                                                elem.getAttribute( "class" ) ||
                                                ""
                                            );
                                        } );
                            },

                            "ATTR": function( name, operator, check ) {
                                return function( elem ) {
                                    var result = Sizzle.attr( elem, name );

                                    if ( result == null ) {
                                        return operator === "!=";
                                    }
                                    if ( !operator ) {
                                        return true;
                                    }

                                    result += "";

                                    /* eslint-disable max-len */

                                    return operator === "=" ? result === check :
                                        operator === "!=" ? result !== check :
                                            operator === "^=" ? check && result.indexOf( check ) === 0 :
                                                operator === "*=" ? check && result.indexOf( check ) > -1 :
                                                    operator === "$=" ? check && result.slice( -check.length ) === check :
                                                        operator === "~=" ? ( " " + result.replace( rwhitespace, " " ) + " " ).indexOf( check ) > -1 :
                                                            operator === "|=" ? result === check || result.slice( 0, check.length + 1 ) === check + "-" :
                                                                false;
                                    /* eslint-enable max-len */

                                };
                            },

                            "CHILD": function( type, what, _argument, first, last ) {
                                var simple = type.slice( 0, 3 ) !== "nth",
                                    forward = type.slice( -4 ) !== "last",
                                    ofType = what === "of-type";

                                return first === 1 && last === 0 ?

                                    // Shortcut for :nth-*(n)
                                    function( elem ) {
                                        return !!elem.parentNode;
                                    } :

                                    function( elem, _context, xml ) {
                                        var cache, uniqueCache, outerCache, node, nodeIndex, start,
                                            dir = simple !== forward ? "nextSibling" : "previousSibling",
                                            parent = elem.parentNode,
                                            name = ofType && elem.nodeName.toLowerCase(),
                                            useCache = !xml && !ofType,
                                            diff = false;

                                        if ( parent ) {

                                            // :(first|last|only)-(child|of-type)
                                            if ( simple ) {
                                                while ( dir ) {
                                                    node = elem;
                                                    while ( ( node = node[ dir ] ) ) {
                                                        if ( ofType ?
                                                            node.nodeName.toLowerCase() === name :
                                                            node.nodeType === 1 ) {

                                                            return false;
                                                        }
                                                    }

                                                    // Reverse direction for :only-* (if we haven't yet done so)
                                                    start = dir = type === "only" && !start && "nextSibling";
                                                }
                                                return true;
                                            }

                                            start = [ forward ? parent.firstChild : parent.lastChild ];

                                            // non-xml :nth-child(...) stores cache data on `parent`
                                            if ( forward && useCache ) {

                                                // Seek `elem` from a previously-cached index

                                                // ...in a gzip-friendly way
                                                node = parent;
                                                outerCache = node[ expando ] || ( node[ expando ] = {} );

                                                // Support: IE <9 only
                                                // Defend against cloned attroperties (jQuery gh-1709)
                                                uniqueCache = outerCache[ node.uniqueID ] ||
                                                    ( outerCache[ node.uniqueID ] = {} );

                                                cache = uniqueCache[ type ] || [];
                                                nodeIndex = cache[ 0 ] === dirruns && cache[ 1 ];
                                                diff = nodeIndex && cache[ 2 ];
                                                node = nodeIndex && parent.childNodes[ nodeIndex ];

                                                while ( ( node = ++nodeIndex && node && node[ dir ] ||

                                                    // Fallback to seeking `elem` from the start
                                                    ( diff = nodeIndex = 0 ) || start.pop() ) ) {

                                                    // When found, cache indexes on `parent` and break
                                                    if ( node.nodeType === 1 && ++diff && node === elem ) {
                                                        uniqueCache[ type ] = [ dirruns, nodeIndex, diff ];
                                                        break;
                                                    }
                                                }

                                            } else {

                                                // Use previously-cached element index if available
                                                if ( useCache ) {

                                                    // ...in a gzip-friendly way
                                                    node = elem;
                                                    outerCache = node[ expando ] || ( node[ expando ] = {} );

                                                    // Support: IE <9 only
                                                    // Defend against cloned attroperties (jQuery gh-1709)
                                                    uniqueCache = outerCache[ node.uniqueID ] ||
                                                        ( outerCache[ node.uniqueID ] = {} );

                                                    cache = uniqueCache[ type ] || [];
                                                    nodeIndex = cache[ 0 ] === dirruns && cache[ 1 ];
                                                    diff = nodeIndex;
                                                }

                                                // xml :nth-child(...)
                                                // or :nth-last-child(...) or :nth(-last)?-of-type(...)
                                                if ( diff === false ) {

                                                    // Use the same loop as above to seek `elem` from the start
                                                    while ( ( node = ++nodeIndex && node && node[ dir ] ||
                                                        ( diff = nodeIndex = 0 ) || start.pop() ) ) {

                                                        if ( ( ofType ?
                                                            node.nodeName.toLowerCase() === name :
                                                            node.nodeType === 1 ) &&
                                                            ++diff ) {

                                                            // Cache the index of each encountered element
                                                            if ( useCache ) {
                                                                outerCache = node[ expando ] ||
                                                                    ( node[ expando ] = {} );

                                                                // Support: IE <9 only
                                                                // Defend against cloned attroperties (jQuery gh-1709)
                                                                uniqueCache = outerCache[ node.uniqueID ] ||
                                                                    ( outerCache[ node.uniqueID ] = {} );

                                                                uniqueCache[ type ] = [ dirruns, diff ];
                                                            }

                                                            if ( node === elem ) {
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            // Incorporate the offset, then check against cycle size
                                            diff -= last;
                                            return diff === first || ( diff % first === 0 && diff / first >= 0 );
                                        }
                                    };
                            },

                            "PSEUDO": function( pseudo, argument ) {

                                // pseudo-class names are case-insensitive
                                // http://www.w3.org/TR/selectors/#pseudo-classes
                                // Prioritize by case sensitivity in case custom pseudos are added with uppercase letters
                                // Remember that setFilters inherits from pseudos
                                var args,
                                    fn = Expr.pseudos[ pseudo ] || Expr.setFilters[ pseudo.toLowerCase() ] ||
                                        Sizzle.error( "unsupported pseudo: " + pseudo );

                                // The user may use createPseudo to indicate that
                                // arguments are needed to create the filter function
                                // just as Sizzle does
                                if ( fn[ expando ] ) {
                                    return fn( argument );
                                }

                                // But maintain support for old signatures
                                if ( fn.length > 1 ) {
                                    args = [ pseudo, pseudo, "", argument ];
                                    return Expr.setFilters.hasOwnProperty( pseudo.toLowerCase() ) ?
                                        markFunction( function( seed, matches ) {
                                            var idx,
                                                matched = fn( seed, argument ),
                                                i = matched.length;
                                            while ( i-- ) {
                                                idx = indexOf( seed, matched[ i ] );
                                                seed[ idx ] = !( matches[ idx ] = matched[ i ] );
                                            }
                                        } ) :
                                        function( elem ) {
                                            return fn( elem, 0, args );
                                        };
                                }

                                return fn;
                            }
                        },

                        pseudos: {

                            // Potentially complex pseudos
                            "not": markFunction( function( selector ) {

                                // Trim the selector passed to compile
                                // to avoid treating leading and trailing
                                // spaces as combinators
                                var input = [],
                                    results = [],
                                    matcher = compile( selector.replace( rtrim, "$1" ) );

                                return matcher[ expando ] ?
                                    markFunction( function( seed, matches, _context, xml ) {
                                        var elem,
                                            unmatched = matcher( seed, null, xml, [] ),
                                            i = seed.length;

                                        // Match elements unmatched by `matcher`
                                        while ( i-- ) {
                                            if ( ( elem = unmatched[ i ] ) ) {
                                                seed[ i ] = !( matches[ i ] = elem );
                                            }
                                        }
                                    } ) :
                                    function( elem, _context, xml ) {
                                        input[ 0 ] = elem;
                                        matcher( input, null, xml, results );

                                        // Don't keep the element (issue #299)
                                        input[ 0 ] = null;
                                        return !results.pop();
                                    };
                            } ),

                            "has": markFunction( function( selector ) {
                                return function( elem ) {
                                    return Sizzle( selector, elem ).length > 0;
                                };
                            } ),

                            "contains": markFunction( function( text ) {
                                text = text.replace( runescape, funescape );
                                return function( elem ) {
                                    return ( elem.textContent || getText( elem ) ).indexOf( text ) > -1;
                                };
                            } ),

                            // "Whether an element is represented by a :lang() selector
                            // is based solely on the element's language value
                            // being equal to the identifier C,
                            // or beginning with the identifier C immediately followed by "-".
                            // The matching of C against the element's language value is performed case-insensitively.
                            // The identifier C does not have to be a valid language name."
                            // http://www.w3.org/TR/selectors/#lang-pseudo
                            "lang": markFunction( function( lang ) {

                                // lang value must be a valid identifier
                                if ( !ridentifier.test( lang || "" ) ) {
                                    Sizzle.error( "unsupported lang: " + lang );
                                }
                                lang = lang.replace( runescape, funescape ).toLowerCase();
                                return function( elem ) {
                                    var elemLang;
                                    do {
                                        if ( ( elemLang = documentIsHTML ?
                                            elem.lang :
                                            elem.getAttribute( "xml:lang" ) || elem.getAttribute( "lang" ) ) ) {

                                            elemLang = elemLang.toLowerCase();
                                            return elemLang === lang || elemLang.indexOf( lang + "-" ) === 0;
                                        }
                                    } while ( ( elem = elem.parentNode ) && elem.nodeType === 1 );
                                    return false;
                                };
                            } ),

                            // Miscellaneous
                            "target": function( elem ) {
                                var hash = window.location && window.location.hash;
                                return hash && hash.slice( 1 ) === elem.id;
                            },

                            "root": function( elem ) {
                                return elem === docElem;
                            },

                            "focus": function( elem ) {
                                return elem === document.activeElement &&
                                    ( !document.hasFocus || document.hasFocus() ) &&
                                    !!( elem.type || elem.href || ~elem.tabIndex );
                            },

                            // Boolean properties
                            "enabled": createDisabledPseudo( false ),
                            "disabled": createDisabledPseudo( true ),

                            "checked": function( elem ) {

                                // In CSS3, :checked should return both checked and selected elements
                                // http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#checked
                                var nodeName = elem.nodeName.toLowerCase();
                                return ( nodeName === "input" && !!elem.checked ) ||
                                    ( nodeName === "option" && !!elem.selected );
                            },

                            "selected": function( elem ) {

                                // Accessing this property makes selected-by-default
                                // options in Safari work properly
                                if ( elem.parentNode ) {
                                    // eslint-disable-next-line no-unused-expressions
                                    elem.parentNode.selectedIndex;
                                }

                                return elem.selected === true;
                            },

                            // Contents
                            "empty": function( elem ) {

                                // http://www.w3.org/TR/selectors/#empty-pseudo
                                // :empty is negated by element (1) or content nodes (text: 3; cdata: 4; entity ref: 5),
                                //   but not by others (comment: 8; processing instruction: 7; etc.)
                                // nodeType < 6 works because attributes (2) do not appear as children
                                for ( elem = elem.firstChild; elem; elem = elem.nextSibling ) {
                                    if ( elem.nodeType < 6 ) {
                                        return false;
                                    }
                                }
                                return true;
                            },

                            "parent": function( elem ) {
                                return !Expr.pseudos[ "empty" ]( elem );
                            },

                            // Element/input types
                            "header": function( elem ) {
                                return rheader.test( elem.nodeName );
                            },

                            "input": function( elem ) {
                                return rinputs.test( elem.nodeName );
                            },

                            "button": function( elem ) {
                                var name = elem.nodeName.toLowerCase();
                                return name === "input" && elem.type === "button" || name === "button";
                            },

                            "text": function( elem ) {
                                var attr;
                                return elem.nodeName.toLowerCase() === "input" &&
                                    elem.type === "text" &&

                                    // Support: IE<8
                                    // New HTML5 attribute values (e.g., "search") appear with elem.type === "text"
                                    ( ( attr = elem.getAttribute( "type" ) ) == null ||
                                        attr.toLowerCase() === "text" );
                            },

                            // Position-in-collection
                            "first": createPositionalPseudo( function() {
                                return [ 0 ];
                            } ),

                            "last": createPositionalPseudo( function( _matchIndexes, length ) {
                                return [ length - 1 ];
                            } ),

                            "eq": createPositionalPseudo( function( _matchIndexes, length, argument ) {
                                return [ argument < 0 ? argument + length : argument ];
                            } ),

                            "even": createPositionalPseudo( function( matchIndexes, length ) {
                                var i = 0;
                                for ( ; i < length; i += 2 ) {
                                    matchIndexes.push( i );
                                }
                                return matchIndexes;
                            } ),

                            "odd": createPositionalPseudo( function( matchIndexes, length ) {
                                var i = 1;
                                for ( ; i < length; i += 2 ) {
                                    matchIndexes.push( i );
                                }
                                return matchIndexes;
                            } ),

                            "lt": createPositionalPseudo( function( matchIndexes, length, argument ) {
                                var i = argument < 0 ?
                                    argument + length :
                                    argument > length ?
                                        length :
                                        argument;
                                for ( ; --i >= 0; ) {
                                    matchIndexes.push( i );
                                }
                                return matchIndexes;
                            } ),

                            "gt": createPositionalPseudo( function( matchIndexes, length, argument ) {
                                var i = argument < 0 ? argument + length : argument;
                                for ( ; ++i < length; ) {
                                    matchIndexes.push( i );
                                }
                                return matchIndexes;
                            } )
                        }
                    };

                    Expr.pseudos[ "nth" ] = Expr.pseudos[ "eq" ];

// Add button/input type pseudos
                    for ( i in { radio: true, checkbox: true, file: true, password: true, image: true } ) {
                        Expr.pseudos[ i ] = createInputPseudo( i );
                    }
                    for ( i in { submit: true, reset: true } ) {
                        Expr.pseudos[ i ] = createButtonPseudo( i );
                    }

// Easy API for creating new setFilters
                    function setFilters() {}
                    setFilters.prototype = Expr.filters = Expr.pseudos;
                    Expr.setFilters = new setFilters();

                    tokenize = Sizzle.tokenize = function( selector, parseOnly ) {
                        var matched, match, tokens, type,
                            soFar, groups, preFilters,
                            cached = tokenCache[ selector + " " ];

                        if ( cached ) {
                            return parseOnly ? 0 : cached.slice( 0 );
                        }

                        soFar = selector;
                        groups = [];
                        preFilters = Expr.preFilter;

                        while ( soFar ) {

                            // Comma and first run
                            if ( !matched || ( match = rcomma.exec( soFar ) ) ) {
                                if ( match ) {

                                    // Don't consume trailing commas as valid
                                    soFar = soFar.slice( match[ 0 ].length ) || soFar;
                                }
                                groups.push( ( tokens = [] ) );
                            }

                            matched = false;

                            // Combinators
                            if ( ( match = rcombinators.exec( soFar ) ) ) {
                                matched = match.shift();
                                tokens.push( {
                                    value: matched,

                                    // Cast descendant combinators to space
                                    type: match[ 0 ].replace( rtrim, " " )
                                } );
                                soFar = soFar.slice( matched.length );
                            }

                            // Filters
                            for ( type in Expr.filter ) {
                                if ( ( match = matchExpr[ type ].exec( soFar ) ) && ( !preFilters[ type ] ||
                                    ( match = preFilters[ type ]( match ) ) ) ) {
                                    matched = match.shift();
                                    tokens.push( {
                                        value: matched,
                                        type: type,
                                        matches: match
                                    } );
                                    soFar = soFar.slice( matched.length );
                                }
                            }

                            if ( !matched ) {
                                break;
                            }
                        }

                        // Return the length of the invalid excess
                        // if we're just parsing
                        // Otherwise, throw an error or return tokens
                        return parseOnly ?
                            soFar.length :
                            soFar ?
                                Sizzle.error( selector ) :

                                // Cache the tokens
                                tokenCache( selector, groups ).slice( 0 );
                    };

                    function toSelector( tokens ) {
                        var i = 0,
                            len = tokens.length,
                            selector = "";
                        for ( ; i < len; i++ ) {
                            selector += tokens[ i ].value;
                        }
                        return selector;
                    }

                    function addCombinator( matcher, combinator, base ) {
                        var dir = combinator.dir,
                            skip = combinator.next,
                            key = skip || dir,
                            checkNonElements = base && key === "parentNode",
                            doneName = done++;

                        return combinator.first ?

                            // Check against closest ancestor/preceding element
                            function( elem, context, xml ) {
                                while ( ( elem = elem[ dir ] ) ) {
                                    if ( elem.nodeType === 1 || checkNonElements ) {
                                        return matcher( elem, context, xml );
                                    }
                                }
                                return false;
                            } :

                            // Check against all ancestor/preceding elements
                            function( elem, context, xml ) {
                                var oldCache, uniqueCache, outerCache,
                                    newCache = [ dirruns, doneName ];

                                // We can't set arbitrary data on XML nodes, so they don't benefit from combinator caching
                                if ( xml ) {
                                    while ( ( elem = elem[ dir ] ) ) {
                                        if ( elem.nodeType === 1 || checkNonElements ) {
                                            if ( matcher( elem, context, xml ) ) {
                                                return true;
                                            }
                                        }
                                    }
                                } else {
                                    while ( ( elem = elem[ dir ] ) ) {
                                        if ( elem.nodeType === 1 || checkNonElements ) {
                                            outerCache = elem[ expando ] || ( elem[ expando ] = {} );

                                            // Support: IE <9 only
                                            // Defend against cloned attroperties (jQuery gh-1709)
                                            uniqueCache = outerCache[ elem.uniqueID ] ||
                                                ( outerCache[ elem.uniqueID ] = {} );

                                            if ( skip && skip === elem.nodeName.toLowerCase() ) {
                                                elem = elem[ dir ] || elem;
                                            } else if ( ( oldCache = uniqueCache[ key ] ) &&
                                                oldCache[ 0 ] === dirruns && oldCache[ 1 ] === doneName ) {

                                                // Assign to newCache so results back-propagate to previous elements
                                                return ( newCache[ 2 ] = oldCache[ 2 ] );
                                            } else {

                                                // Reuse newcache so results back-propagate to previous elements
                                                uniqueCache[ key ] = newCache;

                                                // A match means we're done; a fail means we have to keep checking
                                                if ( ( newCache[ 2 ] = matcher( elem, context, xml ) ) ) {
                                                    return true;
                                                }
                                            }
                                        }
                                    }
                                }
                                return false;
                            };
                    }

                    function elementMatcher( matchers ) {
                        return matchers.length > 1 ?
                            function( elem, context, xml ) {
                                var i = matchers.length;
                                while ( i-- ) {
                                    if ( !matchers[ i ]( elem, context, xml ) ) {
                                        return false;
                                    }
                                }
                                return true;
                            } :
                            matchers[ 0 ];
                    }

                    function multipleContexts( selector, contexts, results ) {
                        var i = 0,
                            len = contexts.length;
                        for ( ; i < len; i++ ) {
                            Sizzle( selector, contexts[ i ], results );
                        }
                        return results;
                    }

                    function condense( unmatched, map, filter, context, xml ) {
                        var elem,
                            newUnmatched = [],
                            i = 0,
                            len = unmatched.length,
                            mapped = map != null;

                        for ( ; i < len; i++ ) {
                            if ( ( elem = unmatched[ i ] ) ) {
                                if ( !filter || filter( elem, context, xml ) ) {
                                    newUnmatched.push( elem );
                                    if ( mapped ) {
                                        map.push( i );
                                    }
                                }
                            }
                        }

                        return newUnmatched;
                    }

                    function setMatcher( preFilter, selector, matcher, postFilter, postFinder, postSelector ) {
                        if ( postFilter && !postFilter[ expando ] ) {
                            postFilter = setMatcher( postFilter );
                        }
                        if ( postFinder && !postFinder[ expando ] ) {
                            postFinder = setMatcher( postFinder, postSelector );
                        }
                        return markFunction( function( seed, results, context, xml ) {
                            var temp, i, elem,
                                preMap = [],
                                postMap = [],
                                preexisting = results.length,

                                // Get initial elements from seed or context
                                elems = seed || multipleContexts(
                                    selector || "*",
                                    context.nodeType ? [ context ] : context,
                                    []
                                ),

                                // Prefilter to get matcher input, preserving a map for seed-results synchronization
                                matcherIn = preFilter && ( seed || !selector ) ?
                                    condense( elems, preMap, preFilter, context, xml ) :
                                    elems,

                                matcherOut = matcher ?

                                    // If we have a postFinder, or filtered seed, or non-seed postFilter or preexisting results,
                                    postFinder || ( seed ? preFilter : preexisting || postFilter ) ?

                                        // ...intermediate processing is necessary
                                        [] :

                                        // ...otherwise use results directly
                                        results :
                                    matcherIn;

                            // Find primary matches
                            if ( matcher ) {
                                matcher( matcherIn, matcherOut, context, xml );
                            }

                            // Apply postFilter
                            if ( postFilter ) {
                                temp = condense( matcherOut, postMap );
                                postFilter( temp, [], context, xml );

                                // Un-match failing elements by moving them back to matcherIn
                                i = temp.length;
                                while ( i-- ) {
                                    if ( ( elem = temp[ i ] ) ) {
                                        matcherOut[ postMap[ i ] ] = !( matcherIn[ postMap[ i ] ] = elem );
                                    }
                                }
                            }

                            if ( seed ) {
                                if ( postFinder || preFilter ) {
                                    if ( postFinder ) {

                                        // Get the final matcherOut by condensing this intermediate into postFinder contexts
                                        temp = [];
                                        i = matcherOut.length;
                                        while ( i-- ) {
                                            if ( ( elem = matcherOut[ i ] ) ) {

                                                // Restore matcherIn since elem is not yet a final match
                                                temp.push( ( matcherIn[ i ] = elem ) );
                                            }
                                        }
                                        postFinder( null, ( matcherOut = [] ), temp, xml );
                                    }

                                    // Move matched elements from seed to results to keep them synchronized
                                    i = matcherOut.length;
                                    while ( i-- ) {
                                        if ( ( elem = matcherOut[ i ] ) &&
                                            ( temp = postFinder ? indexOf( seed, elem ) : preMap[ i ] ) > -1 ) {

                                            seed[ temp ] = !( results[ temp ] = elem );
                                        }
                                    }
                                }

                                // Add elements to results, through postFinder if defined
                            } else {
                                matcherOut = condense(
                                    matcherOut === results ?
                                        matcherOut.splice( preexisting, matcherOut.length ) :
                                        matcherOut
                                );
                                if ( postFinder ) {
                                    postFinder( null, results, matcherOut, xml );
                                } else {
                                    push.apply( results, matcherOut );
                                }
                            }
                        } );
                    }

                    function matcherFromTokens( tokens ) {
                        var checkContext, matcher, j,
                            len = tokens.length,
                            leadingRelative = Expr.relative[ tokens[ 0 ].type ],
                            implicitRelative = leadingRelative || Expr.relative[ " " ],
                            i = leadingRelative ? 1 : 0,

                            // The foundational matcher ensures that elements are reachable from top-level context(s)
                            matchContext = addCombinator( function( elem ) {
                                return elem === checkContext;
                            }, implicitRelative, true ),
                            matchAnyContext = addCombinator( function( elem ) {
                                return indexOf( checkContext, elem ) > -1;
                            }, implicitRelative, true ),
                            matchers = [ function( elem, context, xml ) {
                                var ret = ( !leadingRelative && ( xml || context !== outermostContext ) ) || (
                                    ( checkContext = context ).nodeType ?
                                        matchContext( elem, context, xml ) :
                                        matchAnyContext( elem, context, xml ) );

                                // Avoid hanging onto element (issue #299)
                                checkContext = null;
                                return ret;
                            } ];

                        for ( ; i < len; i++ ) {
                            if ( ( matcher = Expr.relative[ tokens[ i ].type ] ) ) {
                                matchers = [ addCombinator( elementMatcher( matchers ), matcher ) ];
                            } else {
                                matcher = Expr.filter[ tokens[ i ].type ].apply( null, tokens[ i ].matches );

                                // Return special upon seeing a positional matcher
                                if ( matcher[ expando ] ) {

                                    // Find the next relative operator (if any) for proper handling
                                    j = ++i;
                                    for ( ; j < len; j++ ) {
                                        if ( Expr.relative[ tokens[ j ].type ] ) {
                                            break;
                                        }
                                    }
                                    return setMatcher(
                                        i > 1 && elementMatcher( matchers ),
                                        i > 1 && toSelector(

                                        // If the preceding token was a descendant combinator, insert an implicit any-element `*`
                                        tokens
                                            .slice( 0, i - 1 )
                                            .concat( { value: tokens[ i - 2 ].type === " " ? "*" : "" } )
                                        ).replace( rtrim, "$1" ),
                                        matcher,
                                        i < j && matcherFromTokens( tokens.slice( i, j ) ),
                                        j < len && matcherFromTokens( ( tokens = tokens.slice( j ) ) ),
                                        j < len && toSelector( tokens )
                                    );
                                }
                                matchers.push( matcher );
                            }
                        }

                        return elementMatcher( matchers );
                    }

                    function matcherFromGroupMatchers( elementMatchers, setMatchers ) {
                        var bySet = setMatchers.length > 0,
                            byElement = elementMatchers.length > 0,
                            superMatcher = function( seed, context, xml, results, outermost ) {
                                var elem, j, matcher,
                                    matchedCount = 0,
                                    i = "0",
                                    unmatched = seed && [],
                                    setMatched = [],
                                    contextBackup = outermostContext,

                                    // We must always have either seed elements or outermost context
                                    elems = seed || byElement && Expr.find[ "TAG" ]( "*", outermost ),

                                    // Use integer dirruns iff this is the outermost matcher
                                    dirrunsUnique = ( dirruns += contextBackup == null ? 1 : Math.random() || 0.1 ),
                                    len = elems.length;

                                if ( outermost ) {

                                    // Support: IE 11+, Edge 17 - 18+
                                    // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                    // two documents; shallow comparisons work.
                                    // eslint-disable-next-line eqeqeq
                                    outermostContext = context == document || context || outermost;
                                }

                                // Add elements passing elementMatchers directly to results
                                // Support: IE<9, Safari
                                // Tolerate NodeList properties (IE: "length"; Safari: <number>) matching elements by id
                                for ( ; i !== len && ( elem = elems[ i ] ) != null; i++ ) {
                                    if ( byElement && elem ) {
                                        j = 0;

                                        // Support: IE 11+, Edge 17 - 18+
                                        // IE/Edge sometimes throw a "Permission denied" error when strict-comparing
                                        // two documents; shallow comparisons work.
                                        // eslint-disable-next-line eqeqeq
                                        if ( !context && elem.ownerDocument != document ) {
                                            setDocument( elem );
                                            xml = !documentIsHTML;
                                        }
                                        while ( ( matcher = elementMatchers[ j++ ] ) ) {
                                            if ( matcher( elem, context || document, xml ) ) {
                                                results.push( elem );
                                                break;
                                            }
                                        }
                                        if ( outermost ) {
                                            dirruns = dirrunsUnique;
                                        }
                                    }

                                    // Track unmatched elements for set filters
                                    if ( bySet ) {

                                        // They will have gone through all possible matchers
                                        if ( ( elem = !matcher && elem ) ) {
                                            matchedCount--;
                                        }

                                        // Lengthen the array for every element, matched or not
                                        if ( seed ) {
                                            unmatched.push( elem );
                                        }
                                    }
                                }

                                // `i` is now the count of elements visited above, and adding it to `matchedCount`
                                // makes the latter nonnegative.
                                matchedCount += i;

                                // Apply set filters to unmatched elements
                                // NOTE: This can be skipped if there are no unmatched elements (i.e., `matchedCount`
                                // equals `i`), unless we didn't visit _any_ elements in the above loop because we have
                                // no element matchers and no seed.
                                // Incrementing an initially-string "0" `i` allows `i` to remain a string only in that
                                // case, which will result in a "00" `matchedCount` that differs from `i` but is also
                                // numerically zero.
                                if ( bySet && i !== matchedCount ) {
                                    j = 0;
                                    while ( ( matcher = setMatchers[ j++ ] ) ) {
                                        matcher( unmatched, setMatched, context, xml );
                                    }

                                    if ( seed ) {

                                        // Reintegrate element matches to eliminate the need for sorting
                                        if ( matchedCount > 0 ) {
                                            while ( i-- ) {
                                                if ( !( unmatched[ i ] || setMatched[ i ] ) ) {
                                                    setMatched[ i ] = pop.call( results );
                                                }
                                            }
                                        }

                                        // Discard index placeholder values to get only actual matches
                                        setMatched = condense( setMatched );
                                    }

                                    // Add matches to results
                                    push.apply( results, setMatched );

                                    // Seedless set matches succeeding multiple successful matchers stipulate sorting
                                    if ( outermost && !seed && setMatched.length > 0 &&
                                        ( matchedCount + setMatchers.length ) > 1 ) {

                                        Sizzle.uniqueSort( results );
                                    }
                                }

                                // Override manipulation of globals by nested matchers
                                if ( outermost ) {
                                    dirruns = dirrunsUnique;
                                    outermostContext = contextBackup;
                                }

                                return unmatched;
                            };

                        return bySet ?
                            markFunction( superMatcher ) :
                            superMatcher;
                    }

                    compile = Sizzle.compile = function( selector, match /* Internal Use Only */ ) {
                        var i,
                            setMatchers = [],
                            elementMatchers = [],
                            cached = compilerCache[ selector + " " ];

                        if ( !cached ) {

                            // Generate a function of recursive functions that can be used to check each element
                            if ( !match ) {
                                match = tokenize( selector );
                            }
                            i = match.length;
                            while ( i-- ) {
                                cached = matcherFromTokens( match[ i ] );
                                if ( cached[ expando ] ) {
                                    setMatchers.push( cached );
                                } else {
                                    elementMatchers.push( cached );
                                }
                            }

                            // Cache the compiled function
                            cached = compilerCache(
                                selector,
                                matcherFromGroupMatchers( elementMatchers, setMatchers )
                            );

                            // Save selector and tokenization
                            cached.selector = selector;
                        }
                        return cached;
                    };

                    /**
                     * A low-level selection function that works with Sizzle's compiled
                     *  selector functions
                     * @param {String|Function} selector A selector or a pre-compiled
                     *  selector function built with Sizzle.compile
                     * @param {Element} context
                     * @param {Array} [results]
                     * @param {Array} [seed] A set of elements to match against
                     */
                    select = Sizzle.select = function( selector, context, results, seed ) {
                        var i, tokens, token, type, find,
                            compiled = typeof selector === "function" && selector,
                            match = !seed && tokenize( ( selector = compiled.selector || selector ) );

                        results = results || [];

                        // Try to minimize operations if there is only one selector in the list and no seed
                        // (the latter of which guarantees us context)
                        if ( match.length === 1 ) {

                            // Reduce context if the leading compound selector is an ID
                            tokens = match[ 0 ] = match[ 0 ].slice( 0 );
                            if ( tokens.length > 2 && ( token = tokens[ 0 ] ).type === "ID" &&
                                context.nodeType === 9 && documentIsHTML && Expr.relative[ tokens[ 1 ].type ] ) {

                                context = ( Expr.find[ "ID" ]( token.matches[ 0 ]
                                    .replace( runescape, funescape ), context ) || [] )[ 0 ];
                                if ( !context ) {
                                    return results;

                                    // Precompiled matchers will still verify ancestry, so step up a level
                                } else if ( compiled ) {
                                    context = context.parentNode;
                                }

                                selector = selector.slice( tokens.shift().value.length );
                            }

                            // Fetch a seed set for right-to-left matching
                            i = matchExpr[ "needsContext" ].test( selector ) ? 0 : tokens.length;
                            while ( i-- ) {
                                token = tokens[ i ];

                                // Abort if we hit a combinator
                                if ( Expr.relative[ ( type = token.type ) ] ) {
                                    break;
                                }
                                if ( ( find = Expr.find[ type ] ) ) {

                                    // Search, expanding context for leading sibling combinators
                                    if ( ( seed = find(
                                        token.matches[ 0 ].replace( runescape, funescape ),
                                        rsibling.test( tokens[ 0 ].type ) && testContext( context.parentNode ) ||
                                        context
                                    ) ) ) {

                                        // If seed is empty or no tokens remain, we can return early
                                        tokens.splice( i, 1 );
                                        selector = seed.length && toSelector( tokens );
                                        if ( !selector ) {
                                            push.apply( results, seed );
                                            return results;
                                        }

                                        break;
                                    }
                                }
                            }
                        }

                        // Compile and execute a filtering function if one is not provided
                        // Provide `match` to avoid retokenization if we modified the selector above
                        ( compiled || compile( selector, match ) )(
                            seed,
                            context,
                            !documentIsHTML,
                            results,
                            !context || rsibling.test( selector ) && testContext( context.parentNode ) || context
                        );
                        return results;
                    };

// One-time assignments

// Sort stability
                    support.sortStable = expando.split( "" ).sort( sortOrder ).join( "" ) === expando;

// Support: Chrome 14-35+
// Always assume duplicates if they aren't passed to the comparison function
                    support.detectDuplicates = !!hasDuplicate;

// Initialize against the default document
                    setDocument();

// Support: Webkit<537.32 - Safari 6.0.3/Chrome 25 (fixed in Chrome 27)
// Detached nodes confoundingly follow *each other*
                    support.sortDetached = assert( function( el ) {

                        // Should return 1, but returns 4 (following)
                        return el.compareDocumentPosition( document.createElement( "fieldset" ) ) & 1;
                    } );

// Support: IE<8
// Prevent attribute/property "interpolation"
// https://msdn.microsoft.com/en-us/library/ms536429%28VS.85%29.aspx
                    if ( !assert( function( el ) {
                        el.innerHTML = "<a href='#'></a>";
                        return el.firstChild.getAttribute( "href" ) === "#";
                    } ) ) {
                        addHandle( "type|href|height|width", function( elem, name, isXML ) {
                            if ( !isXML ) {
                                return elem.getAttribute( name, name.toLowerCase() === "type" ? 1 : 2 );
                            }
                        } );
                    }

// Support: IE<9
// Use defaultValue in place of getAttribute("value")
                    if ( !support.attributes || !assert( function( el ) {
                        el.innerHTML = "<input/>";
                        el.firstChild.setAttribute( "value", "" );
                        return el.firstChild.getAttribute( "value" ) === "";
                    } ) ) {
                        addHandle( "value", function( elem, _name, isXML ) {
                            if ( !isXML && elem.nodeName.toLowerCase() === "input" ) {
                                return elem.defaultValue;
                            }
                        } );
                    }

// Support: IE<9
// Use getAttributeNode to fetch booleans when getAttribute lies
                    if ( !assert( function( el ) {
                        return el.getAttribute( "disabled" ) == null;
                    } ) ) {
                        addHandle( booleans, function( elem, name, isXML ) {
                            var val;
                            if ( !isXML ) {
                                return elem[ name ] === true ? name.toLowerCase() :
                                    ( val = elem.getAttributeNode( name ) ) && val.specified ?
                                        val.value :
                                        null;
                            }
                        } );
                    }

                    return Sizzle;

                } )( window );



            jQuery.find = Sizzle;
            jQuery.expr = Sizzle.selectors;

// Deprecated
            jQuery.expr[ ":" ] = jQuery.expr.pseudos;
            jQuery.uniqueSort = jQuery.unique = Sizzle.uniqueSort;
            jQuery.text = Sizzle.getText;
            jQuery.isXMLDoc = Sizzle.isXML;
            jQuery.contains = Sizzle.contains;
            jQuery.escapeSelector = Sizzle.escape;




            var dir = function( elem, dir, until ) {
                var matched = [],
                    truncate = until !== undefined;

                while ( ( elem = elem[ dir ] ) && elem.nodeType !== 9 ) {
                    if ( elem.nodeType === 1 ) {
                        if ( truncate && jQuery( elem ).is( until ) ) {
                            break;
                        }
                        matched.push( elem );
                    }
                }
                return matched;
            };


            var siblings = function( n, elem ) {
                var matched = [];

                for ( ; n; n = n.nextSibling ) {
                    if ( n.nodeType === 1 && n !== elem ) {
                        matched.push( n );
                    }
                }

                return matched;
            };


            var rneedsContext = jQuery.expr.match.needsContext;



            function nodeName( elem, name ) {

                return elem.nodeName && elem.nodeName.toLowerCase() === name.toLowerCase();

            };
            var rsingleTag = ( /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i );



// Implement the identical functionality for filter and not
            function winnow( elements, qualifier, not ) {
                if ( isFunction( qualifier ) ) {
                    return jQuery.grep( elements, function( elem, i ) {
                        return !!qualifier.call( elem, i, elem ) !== not;
                    } );
                }

                // Single element
                if ( qualifier.nodeType ) {
                    return jQuery.grep( elements, function( elem ) {
                        return ( elem === qualifier ) !== not;
                    } );
                }

                // Arraylike of elements (jQuery, arguments, Array)
                if ( typeof qualifier !== "string" ) {
                    return jQuery.grep( elements, function( elem ) {
                        return ( indexOf.call( qualifier, elem ) > -1 ) !== not;
                    } );
                }

                // Filtered directly for both simple and complex selectors
                return jQuery.filter( qualifier, elements, not );
            }

            jQuery.filter = function( expr, elems, not ) {
                var elem = elems[ 0 ];

                if ( not ) {
                    expr = ":not(" + expr + ")";
                }

                if ( elems.length === 1 && elem.nodeType === 1 ) {
                    return jQuery.find.matchesSelector( elem, expr ) ? [ elem ] : [];
                }

                return jQuery.find.matches( expr, jQuery.grep( elems, function( elem ) {
                    return elem.nodeType === 1;
                } ) );
            };

            jQuery.fn.extend( {
                find: function( selector ) {
                    var i, ret,
                        len = this.length,
                        self = this;

                    if ( typeof selector !== "string" ) {
                        return this.pushStack( jQuery( selector ).filter( function() {
                            for ( i = 0; i < len; i++ ) {
                                if ( jQuery.contains( self[ i ], this ) ) {
                                    return true;
                                }
                            }
                        } ) );
                    }

                    ret = this.pushStack( [] );

                    for ( i = 0; i < len; i++ ) {
                        jQuery.find( selector, self[ i ], ret );
                    }

                    return len > 1 ? jQuery.uniqueSort( ret ) : ret;
                },
                filter: function( selector ) {
                    return this.pushStack( winnow( this, selector || [], false ) );
                },
                not: function( selector ) {
                    return this.pushStack( winnow( this, selector || [], true ) );
                },
                is: function( selector ) {
                    return !!winnow(
                        this,

                        // If this is a positional/relative selector, check membership in the returned set
                        // so $("p:first").is("p:last") won't return true for a doc with two "p".
                        typeof selector === "string" && rneedsContext.test( selector ) ?
                            jQuery( selector ) :
                            selector || [],
                        false
                    ).length;
                }
            } );


// Initialize a jQuery object


// A central reference to the root jQuery(document)
            var rootjQuery,

                // A simple way to check for HTML strings
                // Prioritize #id over <tag> to avoid XSS via location.hash (#9521)
                // Strict HTML recognition (#11290: must start with <)
                // Shortcut simple #id case for speed
                rquickExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,

                init = jQuery.fn.init = function( selector, context, root ) {
                    var match, elem;

                    // HANDLE: $(""), $(null), $(undefined), $(false)
                    if ( !selector ) {
                        return this;
                    }

                    // Method init() accepts an alternate rootjQuery
                    // so migrate can support jQuery.sub (gh-2101)
                    root = root || rootjQuery;

                    // Handle HTML strings
                    if ( typeof selector === "string" ) {
                        if ( selector[ 0 ] === "<" &&
                            selector[ selector.length - 1 ] === ">" &&
                            selector.length >= 3 ) {

                            // Assume that strings that start and end with <> are HTML and skip the regex check
                            match = [ null, selector, null ];

                        } else {
                            match = rquickExpr.exec( selector );
                        }

                        // Match html or make sure no context is specified for #id
                        if ( match && ( match[ 1 ] || !context ) ) {

                            // HANDLE: $(html) -> $(array)
                            if ( match[ 1 ] ) {
                                context = context instanceof jQuery ? context[ 0 ] : context;

                                // Option to run scripts is true for back-compat
                                // Intentionally let the error be thrown if parseHTML is not present
                                jQuery.merge( this, jQuery.parseHTML(
                                    match[ 1 ],
                                    context && context.nodeType ? context.ownerDocument || context : document,
                                    true
                                ) );

                                // HANDLE: $(html, props)
                                if ( rsingleTag.test( match[ 1 ] ) && jQuery.isPlainObject( context ) ) {
                                    for ( match in context ) {

                                        // Properties of context are called as methods if possible
                                        if ( isFunction( this[ match ] ) ) {
                                            this[ match ]( context[ match ] );

                                            // ...and otherwise set as attributes
                                        } else {
                                            this.attr( match, context[ match ] );
                                        }
                                    }
                                }

                                return this;

                                // HANDLE: $(#id)
                            } else {
                                elem = document.getElementById( match[ 2 ] );

                                if ( elem ) {

                                    // Inject the element directly into the jQuery object
                                    this[ 0 ] = elem;
                                    this.length = 1;
                                }
                                return this;
                            }

                            // HANDLE: $(expr, $(...))
                        } else if ( !context || context.jquery ) {
                            return ( context || root ).find( selector );

                            // HANDLE: $(expr, context)
                            // (which is just equivalent to: $(context).find(expr)
                        } else {
                            return this.constructor( context ).find( selector );
                        }

                        // HANDLE: $(DOMElement)
                    } else if ( selector.nodeType ) {
                        this[ 0 ] = selector;
                        this.length = 1;
                        return this;

                        // HANDLE: $(function)
                        // Shortcut for document ready
                    } else if ( isFunction( selector ) ) {
                        return root.ready !== undefined ?
                            root.ready( selector ) :

                            // Execute immediately if ready is not present
                            selector( jQuery );
                    }

                    return jQuery.makeArray( selector, this );
                };

// Give the init function the jQuery prototype for later instantiation
            init.prototype = jQuery.fn;

// Initialize central reference
            rootjQuery = jQuery( document );


            var rparentsprev = /^(?:parents|prev(?:Until|All))/,

                // Methods guaranteed to produce a unique set when starting from a unique set
                guaranteedUnique = {
                    children: true,
                    contents: true,
                    next: true,
                    prev: true
                };

            jQuery.fn.extend( {
                has: function( target ) {
                    var targets = jQuery( target, this ),
                        l = targets.length;

                    return this.filter( function() {
                        var i = 0;
                        for ( ; i < l; i++ ) {
                            if ( jQuery.contains( this, targets[ i ] ) ) {
                                return true;
                            }
                        }
                    } );
                },

                closest: function( selectors, context ) {
                    var cur,
                        i = 0,
                        l = this.length,
                        matched = [],
                        targets = typeof selectors !== "string" && jQuery( selectors );

                    // Positional selectors never match, since there's no _selection_ context
                    if ( !rneedsContext.test( selectors ) ) {
                        for ( ; i < l; i++ ) {
                            for ( cur = this[ i ]; cur && cur !== context; cur = cur.parentNode ) {

                                // Always skip document fragments
                                if ( cur.nodeType < 11 && ( targets ?
                                    targets.index( cur ) > -1 :

                                    // Don't pass non-elements to Sizzle
                                    cur.nodeType === 1 &&
                                    jQuery.find.matchesSelector( cur, selectors ) ) ) {

                                    matched.push( cur );
                                    break;
                                }
                            }
                        }
                    }

                    return this.pushStack( matched.length > 1 ? jQuery.uniqueSort( matched ) : matched );
                },

                // Determine the position of an element within the set
                index: function( elem ) {

                    // No argument, return index in parent
                    if ( !elem ) {
                        return ( this[ 0 ] && this[ 0 ].parentNode ) ? this.first().prevAll().length : -1;
                    }

                    // Index in selector
                    if ( typeof elem === "string" ) {
                        return indexOf.call( jQuery( elem ), this[ 0 ] );
                    }

                    // Locate the position of the desired element
                    return indexOf.call( this,

                        // If it receives a jQuery object, the first element is used
                        elem.jquery ? elem[ 0 ] : elem
                    );
                },

                add: function( selector, context ) {
                    return this.pushStack(
                        jQuery.uniqueSort(
                            jQuery.merge( this.get(), jQuery( selector, context ) )
                        )
                    );
                },

                addBack: function( selector ) {
                    return this.add( selector == null ?
                        this.prevObject : this.prevObject.filter( selector )
                    );
                }
            } );

            function sibling( cur, dir ) {
                while ( ( cur = cur[ dir ] ) && cur.nodeType !== 1 ) {}
                return cur;
            }

            jQuery.each( {
                parent: function( elem ) {
                    var parent = elem.parentNode;
                    return parent && parent.nodeType !== 11 ? parent : null;
                },
                parents: function( elem ) {
                    return dir( elem, "parentNode" );
                },
                parentsUntil: function( elem, _i, until ) {
                    return dir( elem, "parentNode", until );
                },
                next: function( elem ) {
                    return sibling( elem, "nextSibling" );
                },
                prev: function( elem ) {
                    return sibling( elem, "previousSibling" );
                },
                nextAll: function( elem ) {
                    return dir( elem, "nextSibling" );
                },
                prevAll: function( elem ) {
                    return dir( elem, "previousSibling" );
                },
                nextUntil: function( elem, _i, until ) {
                    return dir( elem, "nextSibling", until );
                },
                prevUntil: function( elem, _i, until ) {
                    return dir( elem, "previousSibling", until );
                },
                siblings: function( elem ) {
                    return siblings( ( elem.parentNode || {} ).firstChild, elem );
                },
                children: function( elem ) {
                    return siblings( elem.firstChild );
                },
                contents: function( elem ) {
                    if ( elem.contentDocument != null &&

                        // Support: IE 11+
                        // <object> elements with no `data` attribute has an object
                        // `contentDocument` with a `null` prototype.
                        getProto( elem.contentDocument ) ) {

                        return elem.contentDocument;
                    }

                    // Support: IE 9 - 11 only, iOS 7 only, Android Browser <=4.3 only
                    // Treat the template element as a regular one in browsers that
                    // don't support it.
                    if ( nodeName( elem, "template" ) ) {
                        elem = elem.content || elem;
                    }

                    return jQuery.merge( [], elem.childNodes );
                }
            }, function( name, fn ) {
                jQuery.fn[ name ] = function( until, selector ) {
                    var matched = jQuery.map( this, fn, until );

                    if ( name.slice( -5 ) !== "Until" ) {
                        selector = until;
                    }

                    if ( selector && typeof selector === "string" ) {
                        matched = jQuery.filter( selector, matched );
                    }

                    if ( this.length > 1 ) {

                        // Remove duplicates
                        if ( !guaranteedUnique[ name ] ) {
                            jQuery.uniqueSort( matched );
                        }

                        // Reverse order for parents* and prev-derivatives
                        if ( rparentsprev.test( name ) ) {
                            matched.reverse();
                        }
                    }

                    return this.pushStack( matched );
                };
            } );
            var rnothtmlwhite = ( /[^\x20\t\r\n\f]+/g );



// Convert String-formatted options into Object-formatted ones
            function createOptions( options ) {
                var object = {};
                jQuery.each( options.match( rnothtmlwhite ) || [], function( _, flag ) {
                    object[ flag ] = true;
                } );
                return object;
            }

            /*
 * Create a callback list using the following parameters:
 *
 *	options: an optional list of space-separated options that will change how
 *			the callback list behaves or a more traditional option object
 *
 * By default a callback list will act like an event callback list and can be
 * "fired" multiple times.
 *
 * Possible options:
 *
 *	once:			will ensure the callback list can only be fired once (like a Deferred)
 *
 *	memory:			will keep track of previous values and will call any callback added
 *					after the list has been fired right away with the latest "memorized"
 *					values (like a Deferred)
 *
 *	unique:			will ensure a callback can only be added once (no duplicate in the list)
 *
 *	stopOnFalse:	interrupt callings when a callback returns false
 *
 */
            jQuery.Callbacks = function( options ) {

                // Convert options from String-formatted to Object-formatted if needed
                // (we check in cache first)
                options = typeof options === "string" ?
                    createOptions( options ) :
                    jQuery.extend( {}, options );

                var // Flag to know if list is currently firing
                    firing,

                    // Last fire value for non-forgettable lists
                    memory,

                    // Flag to know if list was already fired
                    fired,

                    // Flag to prevent firing
                    locked,

                    // Actual callback list
                    list = [],

                    // Queue of execution data for repeatable lists
                    queue = [],

                    // Index of currently firing callback (modified by add/remove as needed)
                    firingIndex = -1,

                    // Fire callbacks
                    fire = function() {

                        // Enforce single-firing
                        locked = locked || options.once;

                        // Execute callbacks for all pending executions,
                        // respecting firingIndex overrides and runtime changes
                        fired = firing = true;
                        for ( ; queue.length; firingIndex = -1 ) {
                            memory = queue.shift();
                            while ( ++firingIndex < list.length ) {

                                // Run callback and check for early termination
                                if ( list[ firingIndex ].apply( memory[ 0 ], memory[ 1 ] ) === false &&
                                    options.stopOnFalse ) {

                                    // Jump to end and forget the data so .add doesn't re-fire
                                    firingIndex = list.length;
                                    memory = false;
                                }
                            }
                        }

                        // Forget the data if we're done with it
                        if ( !options.memory ) {
                            memory = false;
                        }

                        firing = false;

                        // Clean up if we're done firing for good
                        if ( locked ) {

                            // Keep an empty list if we have data for future add calls
                            if ( memory ) {
                                list = [];

                                // Otherwise, this object is spent
                            } else {
                                list = "";
                            }
                        }
                    },

                    // Actual Callbacks object
                    self = {

                        // Add a callback or a collection of callbacks to the list
                        add: function() {
                            if ( list ) {

                                // If we have memory from a past run, we should fire after adding
                                if ( memory && !firing ) {
                                    firingIndex = list.length - 1;
                                    queue.push( memory );
                                }

                                ( function add( args ) {
                                    jQuery.each( args, function( _, arg ) {
                                        if ( isFunction( arg ) ) {
                                            if ( !options.unique || !self.has( arg ) ) {
                                                list.push( arg );
                                            }
                                        } else if ( arg && arg.length && toType( arg ) !== "string" ) {

                                            // Inspect recursively
                                            add( arg );
                                        }
                                    } );
                                } )( arguments );

                                if ( memory && !firing ) {
                                    fire();
                                }
                            }
                            return this;
                        },

                        // Remove a callback from the list
                        remove: function() {
                            jQuery.each( arguments, function( _, arg ) {
                                var index;
                                while ( ( index = jQuery.inArray( arg, list, index ) ) > -1 ) {
                                    list.splice( index, 1 );

                                    // Handle firing indexes
                                    if ( index <= firingIndex ) {
                                        firingIndex--;
                                    }
                                }
                            } );
                            return this;
                        },

                        // Check if a given callback is in the list.
                        // If no argument is given, return whether or not list has callbacks attached.
                        has: function( fn ) {
                            return fn ?
                                jQuery.inArray( fn, list ) > -1 :
                                list.length > 0;
                        },

                        // Remove all callbacks from the list
                        empty: function() {
                            if ( list ) {
                                list = [];
                            }
                            return this;
                        },

                        // Disable .fire and .add
                        // Abort any current/pending executions
                        // Clear all callbacks and values
                        disable: function() {
                            locked = queue = [];
                            list = memory = "";
                            return this;
                        },
                        disabled: function() {
                            return !list;
                        },

                        // Disable .fire
                        // Also disable .add unless we have memory (since it would have no effect)
                        // Abort any pending executions
                        lock: function() {
                            locked = queue = [];
                            if ( !memory && !firing ) {
                                list = memory = "";
                            }
                            return this;
                        },
                        locked: function() {
                            return !!locked;
                        },

                        // Call all callbacks with the given context and arguments
                        fireWith: function( context, args ) {
                            if ( !locked ) {
                                args = args || [];
                                args = [ context, args.slice ? args.slice() : args ];
                                queue.push( args );
                                if ( !firing ) {
                                    fire();
                                }
                            }
                            return this;
                        },

                        // Call all the callbacks with the given arguments
                        fire: function() {
                            self.fireWith( this, arguments );
                            return this;
                        },

                        // To know if the callbacks have already been called at least once
                        fired: function() {
                            return !!fired;
                        }
                    };

                return self;
            };


            function Identity( v ) {
                return v;
            }
            function Thrower( ex ) {
                throw ex;
            }

            function adoptValue( value, resolve, reject, noValue ) {
                var method;

                try {

                    // Check for promise aspect first to privilege synchronous behavior
                    if ( value && isFunction( ( method = value.promise ) ) ) {
                        method.call( value ).done( resolve ).fail( reject );

                        // Other thenables
                    } else if ( value && isFunction( ( method = value.then ) ) ) {
                        method.call( value, resolve, reject );

                        // Other non-thenables
                    } else {

                        // Control `resolve` arguments by letting Array#slice cast boolean `noValue` to integer:
                        // * false: [ value ].slice( 0 ) => resolve( value )
                        // * true: [ value ].slice( 1 ) => resolve()
                        resolve.apply( undefined, [ value ].slice( noValue ) );
                    }

                    // For Promises/A+, convert exceptions into rejections
                    // Since jQuery.when doesn't unwrap thenables, we can skip the extra checks appearing in
                    // Deferred#then to conditionally suppress rejection.
                } catch ( value ) {

                    // Support: Android 4.0 only
                    // Strict mode functions invoked without .call/.apply get global-object context
                    reject.apply( undefined, [ value ] );
                }
            }

            jQuery.extend( {

                Deferred: function( func ) {
                    var tuples = [

                            // action, add listener, callbacks,
                            // ... .then handlers, argument index, [final state]
                            [ "notify", "progress", jQuery.Callbacks( "memory" ),
                                jQuery.Callbacks( "memory" ), 2 ],
                            [ "resolve", "done", jQuery.Callbacks( "once memory" ),
                                jQuery.Callbacks( "once memory" ), 0, "resolved" ],
                            [ "reject", "fail", jQuery.Callbacks( "once memory" ),
                                jQuery.Callbacks( "once memory" ), 1, "rejected" ]
                        ],
                        state = "pending",
                        promise = {
                            state: function() {
                                return state;
                            },
                            always: function() {
                                deferred.done( arguments ).fail( arguments );
                                return this;
                            },
                            "catch": function( fn ) {
                                return promise.then( null, fn );
                            },

                            // Keep pipe for back-compat
                            pipe: function( /* fnDone, fnFail, fnProgress */ ) {
                                var fns = arguments;

                                return jQuery.Deferred( function( newDefer ) {
                                    jQuery.each( tuples, function( _i, tuple ) {

                                        // Map tuples (progress, done, fail) to arguments (done, fail, progress)
                                        var fn = isFunction( fns[ tuple[ 4 ] ] ) && fns[ tuple[ 4 ] ];

                                        // deferred.progress(function() { bind to newDefer or newDefer.notify })
                                        // deferred.done(function() { bind to newDefer or newDefer.resolve })
                                        // deferred.fail(function() { bind to newDefer or newDefer.reject })
                                        deferred[ tuple[ 1 ] ]( function() {
                                            var returned = fn && fn.apply( this, arguments );
                                            if ( returned && isFunction( returned.promise ) ) {
                                                returned.promise()
                                                    .progress( newDefer.notify )
                                                    .done( newDefer.resolve )
                                                    .fail( newDefer.reject );
                                            } else {
                                                newDefer[ tuple[ 0 ] + "With" ](
                                                    this,
                                                    fn ? [ returned ] : arguments
                                                );
                                            }
                                        } );
                                    } );
                                    fns = null;
                                } ).promise();
                            },
                            then: function( onFulfilled, onRejected, onProgress ) {
                                var maxDepth = 0;
                                function resolve( depth, deferred, handler, special ) {
                                    return function() {
                                        var that = this,
                                            args = arguments,
                                            mightThrow = function() {
                                                var returned, then;

                                                // Support: Promises/A+ section 2.3.3.3.3
                                                // https://promisesaplus.com/#point-59
                                                // Ignore double-resolution attempts
                                                if ( depth < maxDepth ) {
                                                    return;
                                                }

                                                returned = handler.apply( that, args );

                                                // Support: Promises/A+ section 2.3.1
                                                // https://promisesaplus.com/#point-48
                                                if ( returned === deferred.promise() ) {
                                                    throw new TypeError( "Thenable self-resolution" );
                                                }

                                                // Support: Promises/A+ sections 2.3.3.1, 3.5
                                                // https://promisesaplus.com/#point-54
                                                // https://promisesaplus.com/#point-75
                                                // Retrieve `then` only once
                                                then = returned &&

                                                    // Support: Promises/A+ section 2.3.4
                                                    // https://promisesaplus.com/#point-64
                                                    // Only check objects and functions for thenability
                                                    ( typeof returned === "object" ||
                                                        typeof returned === "function" ) &&
                                                    returned.then;

                                                // Handle a returned thenable
                                                if ( isFunction( then ) ) {

                                                    // Special processors (notify) just wait for resolution
                                                    if ( special ) {
                                                        then.call(
                                                            returned,
                                                            resolve( maxDepth, deferred, Identity, special ),
                                                            resolve( maxDepth, deferred, Thrower, special )
                                                        );

                                                        // Normal processors (resolve) also hook into progress
                                                    } else {

                                                        // ...and disregard older resolution values
                                                        maxDepth++;

                                                        then.call(
                                                            returned,
                                                            resolve( maxDepth, deferred, Identity, special ),
                                                            resolve( maxDepth, deferred, Thrower, special ),
                                                            resolve( maxDepth, deferred, Identity,
                                                                deferred.notifyWith )
                                                        );
                                                    }

                                                    // Handle all other returned values
                                                } else {

                                                    // Only substitute handlers pass on context
                                                    // and multiple values (non-spec behavior)
                                                    if ( handler !== Identity ) {
                                                        that = undefined;
                                                        args = [ returned ];
                                                    }

                                                    // Process the value(s)
                                                    // Default process is resolve
                                                    ( special || deferred.resolveWith )( that, args );
                                                }
                                            },

                                            // Only normal processors (resolve) catch and reject exceptions
                                            process = special ?
                                                mightThrow :
                                                function() {
                                                    try {
                                                        mightThrow();
                                                    } catch ( e ) {

                                                        if ( jQuery.Deferred.exceptionHook ) {
                                                            jQuery.Deferred.exceptionHook( e,
                                                                process.stackTrace );
                                                        }

                                                        // Support: Promises/A+ section 2.3.3.3.4.1
                                                        // https://promisesaplus.com/#point-61
                                                        // Ignore post-resolution exceptions
                                                        if ( depth + 1 >= maxDepth ) {

                                                            // Only substitute handlers pass on context
                                                            // and multiple values (non-spec behavior)
                                                            if ( handler !== Thrower ) {
                                                                that = undefined;
                                                                args = [ e ];
                                                            }

                                                            deferred.rejectWith( that, args );
                                                        }
                                                    }
                                                };

                                        // Support: Promises/A+ section 2.3.3.3.1
                                        // https://promisesaplus.com/#point-57
                                        // Re-resolve promises immediately to dodge false rejection from
                                        // subsequent errors
                                        if ( depth ) {
                                            process();
                                        } else {

                                            // Call an optional hook to record the stack, in case of exception
                                            // since it's otherwise lost when execution goes async
                                            if ( jQuery.Deferred.getStackHook ) {
                                                process.stackTrace = jQuery.Deferred.getStackHook();
                                            }
                                            window.setTimeout( process );
                                        }
                                    };
                                }

                                return jQuery.Deferred( function( newDefer ) {

                                    // progress_handlers.add( ... )
                                    tuples[ 0 ][ 3 ].add(
                                        resolve(
                                            0,
                                            newDefer,
                                            isFunction( onProgress ) ?
                                                onProgress :
                                                Identity,
                                            newDefer.notifyWith
                                        )
                                    );

                                    // fulfilled_handlers.add( ... )
                                    tuples[ 1 ][ 3 ].add(
                                        resolve(
                                            0,
                                            newDefer,
                                            isFunction( onFulfilled ) ?
                                                onFulfilled :
                                                Identity
                                        )
                                    );

                                    // rejected_handlers.add( ... )
                                    tuples[ 2 ][ 3 ].add(
                                        resolve(
                                            0,
                                            newDefer,
                                            isFunction( onRejected ) ?
                                                onRejected :
                                                Thrower
                                        )
                                    );
                                } ).promise();
                            },

                            // Get a promise for this deferred
                            // If obj is provided, the promise aspect is added to the object
                            promise: function( obj ) {
                                return obj != null ? jQuery.extend( obj, promise ) : promise;
                            }
                        },
                        deferred = {};

                    // Add list-specific methods
                    jQuery.each( tuples, function( i, tuple ) {
                        var list = tuple[ 2 ],
                            stateString = tuple[ 5 ];

                        // promise.progress = list.add
                        // promise.done = list.add
                        // promise.fail = list.add
                        promise[ tuple[ 1 ] ] = list.add;

                        // Handle state
                        if ( stateString ) {
                            list.add(
                                function() {

                                    // state = "resolved" (i.e., fulfilled)
                                    // state = "rejected"
                                    state = stateString;
                                },

                                // rejected_callbacks.disable
                                // fulfilled_callbacks.disable
                                tuples[ 3 - i ][ 2 ].disable,

                                // rejected_handlers.disable
                                // fulfilled_handlers.disable
                                tuples[ 3 - i ][ 3 ].disable,

                                // progress_callbacks.lock
                                tuples[ 0 ][ 2 ].lock,

                                // progress_handlers.lock
                                tuples[ 0 ][ 3 ].lock
                            );
                        }

                        // progress_handlers.fire
                        // fulfilled_handlers.fire
                        // rejected_handlers.fire
                        list.add( tuple[ 3 ].fire );

                        // deferred.notify = function() { deferred.notifyWith(...) }
                        // deferred.resolve = function() { deferred.resolveWith(...) }
                        // deferred.reject = function() { deferred.rejectWith(...) }
                        deferred[ tuple[ 0 ] ] = function() {
                            deferred[ tuple[ 0 ] + "With" ]( this === deferred ? undefined : this, arguments );
                            return this;
                        };

                        // deferred.notifyWith = list.fireWith
                        // deferred.resolveWith = list.fireWith
                        // deferred.rejectWith = list.fireWith
                        deferred[ tuple[ 0 ] + "With" ] = list.fireWith;
                    } );

                    // Make the deferred a promise
                    promise.promise( deferred );

                    // Call given func if any
                    if ( func ) {
                        func.call( deferred, deferred );
                    }

                    // All done!
                    return deferred;
                },

                // Deferred helper
                when: function( singleValue ) {
                    var

                        // count of uncompleted subordinates
                        remaining = arguments.length,

                        // count of unprocessed arguments
                        i = remaining,

                        // subordinate fulfillment data
                        resolveContexts = Array( i ),
                        resolveValues = slice.call( arguments ),

                        // the master Deferred
                        master = jQuery.Deferred(),

                        // subordinate callback factory
                        updateFunc = function( i ) {
                            return function( value ) {
                                resolveContexts[ i ] = this;
                                resolveValues[ i ] = arguments.length > 1 ? slice.call( arguments ) : value;
                                if ( !( --remaining ) ) {
                                    master.resolveWith( resolveContexts, resolveValues );
                                }
                            };
                        };

                    // Single- and empty arguments are adopted like Promise.resolve
                    if ( remaining <= 1 ) {
                        adoptValue( singleValue, master.done( updateFunc( i ) ).resolve, master.reject,
                            !remaining );

                        // Use .then() to unwrap secondary thenables (cf. gh-3000)
                        if ( master.state() === "pending" ||
                            isFunction( resolveValues[ i ] && resolveValues[ i ].then ) ) {

                            return master.then();
                        }
                    }

                    // Multiple arguments are aggregated like Promise.all array elements
                    while ( i-- ) {
                        adoptValue( resolveValues[ i ], updateFunc( i ), master.reject );
                    }

                    return master.promise();
                }
            } );


// These usually indicate a programmer mistake during development,
// warn about them ASAP rather than swallowing them by default.
            var rerrorNames = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;

            jQuery.Deferred.exceptionHook = function( error, stack ) {

                // Support: IE 8 - 9 only
                // Console exists when dev tools are open, which can happen at any time
                if ( window.console && window.console.warn && error && rerrorNames.test( error.name ) ) {
                    window.console.warn( "jQuery.Deferred exception: " + error.message, error.stack, stack );
                }
            };




            jQuery.readyException = function( error ) {
                window.setTimeout( function() {
                    throw error;
                } );
            };




// The deferred used on DOM ready
            var readyList = jQuery.Deferred();

            jQuery.fn.ready = function( fn ) {

                readyList
                    .then( fn )

                    // Wrap jQuery.readyException in a function so that the lookup
                    // happens at the time of error handling instead of callback
                    // registration.
                    .catch( function( error ) {
                        jQuery.readyException( error );
                    } );

                return this;
            };

            jQuery.extend( {

                // Is the DOM ready to be used? Set to true once it occurs.
                isReady: false,

                // A counter to track how many items to wait for before
                // the ready event fires. See #6781
                readyWait: 1,

                // Handle when the DOM is ready
                ready: function( wait ) {

                    // Abort if there are pending holds or we're already ready
                    if ( wait === true ? --jQuery.readyWait : jQuery.isReady ) {
                        return;
                    }

                    // Remember that the DOM is ready
                    jQuery.isReady = true;

                    // If a normal DOM Ready event fired, decrement, and wait if need be
                    if ( wait !== true && --jQuery.readyWait > 0 ) {
                        return;
                    }

                    // If there are functions bound, to execute
                    readyList.resolveWith( document, [ jQuery ] );
                }
            } );

            jQuery.ready.then = readyList.then;

// The ready event handler and self cleanup method
            function completed() {
                document.removeEventListener( "DOMContentLoaded", completed );
                window.removeEventListener( "load", completed );
                jQuery.ready();
            }

// Catch cases where $(document).ready() is called
// after the browser event has already occurred.
// Support: IE <=9 - 10 only
// Older IE sometimes signals "interactive" too soon
            if ( document.readyState === "complete" ||
                ( document.readyState !== "loading" && !document.documentElement.doScroll ) ) {

                // Handle it asynchronously to allow scripts the opportunity to delay ready
                window.setTimeout( jQuery.ready );

            } else {

                // Use the handy event callback
                document.addEventListener( "DOMContentLoaded", completed );

                // A fallback to window.onload, that will always work
                window.addEventListener( "load", completed );
            }




// Multifunctional method to get and set values of a collection
// The value/s can optionally be executed if it's a function
            var access = function( elems, fn, key, value, chainable, emptyGet, raw ) {
                var i = 0,
                    len = elems.length,
                    bulk = key == null;

                // Sets many values
                if ( toType( key ) === "object" ) {
                    chainable = true;
                    for ( i in key ) {
                        access( elems, fn, i, key[ i ], true, emptyGet, raw );
                    }

                    // Sets one value
                } else if ( value !== undefined ) {
                    chainable = true;

                    if ( !isFunction( value ) ) {
                        raw = true;
                    }

                    if ( bulk ) {

                        // Bulk operations run against the entire set
                        if ( raw ) {
                            fn.call( elems, value );
                            fn = null;

                            // ...except when executing function values
                        } else {
                            bulk = fn;
                            fn = function( elem, _key, value ) {
                                return bulk.call( jQuery( elem ), value );
                            };
                        }
                    }

                    if ( fn ) {
                        for ( ; i < len; i++ ) {
                            fn(
                                elems[ i ], key, raw ?
                                    value :
                                    value.call( elems[ i ], i, fn( elems[ i ], key ) )
                            );
                        }
                    }
                }

                if ( chainable ) {
                    return elems;
                }

                // Gets
                if ( bulk ) {
                    return fn.call( elems );
                }

                return len ? fn( elems[ 0 ], key ) : emptyGet;
            };


// Matches dashed string for camelizing
            var rmsPrefix = /^-ms-/,
                rdashAlpha = /-([a-z])/g;

// Used by camelCase as callback to replace()
            function fcamelCase( _all, letter ) {
                return letter.toUpperCase();
            }

// Convert dashed to camelCase; used by the css and data modules
// Support: IE <=9 - 11, Edge 12 - 15
// Microsoft forgot to hump their vendor prefix (#9572)
            function camelCase( string ) {
                return string.replace( rmsPrefix, "ms-" ).replace( rdashAlpha, fcamelCase );
            }
            var acceptData = function( owner ) {

                // Accepts only:
                //  - Node
                //    - Node.ELEMENT_NODE
                //    - Node.DOCUMENT_NODE
                //  - Object
                //    - Any
                return owner.nodeType === 1 || owner.nodeType === 9 || !( +owner.nodeType );
            };




            function Data() {
                this.expando = jQuery.expando + Data.uid++;
            }

            Data.uid = 1;

            Data.prototype = {

                cache: function( owner ) {

                    // Check if the owner object already has a cache
                    var value = owner[ this.expando ];

                    // If not, create one
                    if ( !value ) {
                        value = {};

                        // We can accept data for non-element nodes in modern browsers,
                        // but we should not, see #8335.
                        // Always return an empty object.
                        if ( acceptData( owner ) ) {

                            // If it is a node unlikely to be stringify-ed or looped over
                            // use plain assignment
                            if ( owner.nodeType ) {
                                owner[ this.expando ] = value;

                                // Otherwise secure it in a non-enumerable property
                                // configurable must be true to allow the property to be
                                // deleted when data is removed
                            } else {
                                Object.defineProperty( owner, this.expando, {
                                    value: value,
                                    configurable: true
                                } );
                            }
                        }
                    }

                    return value;
                },
                set: function( owner, data, value ) {
                    var prop,
                        cache = this.cache( owner );

                    // Handle: [ owner, key, value ] args
                    // Always use camelCase key (gh-2257)
                    if ( typeof data === "string" ) {
                        cache[ camelCase( data ) ] = value;

                        // Handle: [ owner, { properties } ] args
                    } else {

                        // Copy the properties one-by-one to the cache object
                        for ( prop in data ) {
                            cache[ camelCase( prop ) ] = data[ prop ];
                        }
                    }
                    return cache;
                },
                get: function( owner, key ) {
                    return key === undefined ?
                        this.cache( owner ) :

                        // Always use camelCase key (gh-2257)
                        owner[ this.expando ] && owner[ this.expando ][ camelCase( key ) ];
                },
                access: function( owner, key, value ) {

                    // In cases where either:
                    //
                    //   1. No key was specified
                    //   2. A string key was specified, but no value provided
                    //
                    // Take the "read" path and allow the get method to determine
                    // which value to return, respectively either:
                    //
                    //   1. The entire cache object
                    //   2. The data stored at the key
                    //
                    if ( key === undefined ||
                        ( ( key && typeof key === "string" ) && value === undefined ) ) {

                        return this.get( owner, key );
                    }

                    // When the key is not a string, or both a key and value
                    // are specified, set or extend (existing objects) with either:
                    //
                    //   1. An object of properties
                    //   2. A key and value
                    //
                    this.set( owner, key, value );

                    // Since the "set" path can have two possible entry points
                    // return the expected data based on which path was taken[*]
                    return value !== undefined ? value : key;
                },
                remove: function( owner, key ) {
                    var i,
                        cache = owner[ this.expando ];

                    if ( cache === undefined ) {
                        return;
                    }

                    if ( key !== undefined ) {

                        // Support array or space separated string of keys
                        if ( Array.isArray( key ) ) {

                            // If key is an array of keys...
                            // We always set camelCase keys, so remove that.
                            key = key.map( camelCase );
                        } else {
                            key = camelCase( key );

                            // If a key with the spaces exists, use it.
                            // Otherwise, create an array by matching non-whitespace
                            key = key in cache ?
                                [ key ] :
                                ( key.match( rnothtmlwhite ) || [] );
                        }

                        i = key.length;

                        while ( i-- ) {
                            delete cache[ key[ i ] ];
                        }
                    }

                    // Remove the expando if there's no more data
                    if ( key === undefined || jQuery.isEmptyObject( cache ) ) {

                        // Support: Chrome <=35 - 45
                        // Webkit & Blink performance suffers when deleting properties
                        // from DOM nodes, so set to undefined instead
                        // https://bugs.chromium.org/p/chromium/issues/detail?id=378607 (bug restricted)
                        if ( owner.nodeType ) {
                            owner[ this.expando ] = undefined;
                        } else {
                            delete owner[ this.expando ];
                        }
                    }
                },
                hasData: function( owner ) {
                    var cache = owner[ this.expando ];
                    return cache !== undefined && !jQuery.isEmptyObject( cache );
                }
            };
            var dataPriv = new Data();

            var dataUser = new Data();



//	Implementation Summary
//
//	1. Enforce API surface and semantic compatibility with 1.9.x branch
//	2. Improve the module's maintainability by reducing the storage
//		paths to a single mechanism.
//	3. Use the same single mechanism to support "private" and "user" data.
//	4. _Never_ expose "private" data to user code (TODO: Drop _data, _removeData)
//	5. Avoid exposing implementation details on user objects (eg. expando properties)
//	6. Provide a clear path for implementation upgrade to WeakMap in 2014

            var rbrace = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
                rmultiDash = /[A-Z]/g;

            function getData( data ) {
                if ( data === "true" ) {
                    return true;
                }

                if ( data === "false" ) {
                    return false;
                }

                if ( data === "null" ) {
                    return null;
                }

                // Only convert to a number if it doesn't change the string
                if ( data === +data + "" ) {
                    return +data;
                }

                if ( rbrace.test( data ) ) {
                    return JSON.parse( data );
                }

                return data;
            }

            function dataAttr( elem, key, data ) {
                var name;

                // If nothing was found internally, try to fetch any
                // data from the HTML5 data-* attribute
                if ( data === undefined && elem.nodeType === 1 ) {
                    name = "data-" + key.replace( rmultiDash, "-$&" ).toLowerCase();
                    data = elem.getAttribute( name );

                    if ( typeof data === "string" ) {
                        try {
                            data = getData( data );
                        } catch ( e ) {}

                        // Make sure we set the data so it isn't changed later
                        dataUser.set( elem, key, data );
                    } else {
                        data = undefined;
                    }
                }
                return data;
            }

            jQuery.extend( {
                hasData: function( elem ) {
                    return dataUser.hasData( elem ) || dataPriv.hasData( elem );
                },

                data: function( elem, name, data ) {
                    return dataUser.access( elem, name, data );
                },

                removeData: function( elem, name ) {
                    dataUser.remove( elem, name );
                },

                // TODO: Now that all calls to _data and _removeData have been replaced
                // with direct calls to dataPriv methods, these can be deprecated.
                _data: function( elem, name, data ) {
                    return dataPriv.access( elem, name, data );
                },

                _removeData: function( elem, name ) {
                    dataPriv.remove( elem, name );
                }
            } );

            jQuery.fn.extend( {
                data: function( key, value ) {
                    var i, name, data,
                        elem = this[ 0 ],
                        attrs = elem && elem.attributes;

                    // Gets all values
                    if ( key === undefined ) {
                        if ( this.length ) {
                            data = dataUser.get( elem );

                            if ( elem.nodeType === 1 && !dataPriv.get( elem, "hasDataAttrs" ) ) {
                                i = attrs.length;
                                while ( i-- ) {

                                    // Support: IE 11 only
                                    // The attrs elements can be null (#14894)
                                    if ( attrs[ i ] ) {
                                        name = attrs[ i ].name;
                                        if ( name.indexOf( "data-" ) === 0 ) {
                                            name = camelCase( name.slice( 5 ) );
                                            dataAttr( elem, name, data[ name ] );
                                        }
                                    }
                                }
                                dataPriv.set( elem, "hasDataAttrs", true );
                            }
                        }

                        return data;
                    }

                    // Sets multiple values
                    if ( typeof key === "object" ) {
                        return this.each( function() {
                            dataUser.set( this, key );
                        } );
                    }

                    return access( this, function( value ) {
                        var data;

                        // The calling jQuery object (element matches) is not empty
                        // (and therefore has an element appears at this[ 0 ]) and the
                        // `value` parameter was not undefined. An empty jQuery object
                        // will result in `undefined` for elem = this[ 0 ] which will
                        // throw an exception if an attempt to read a data cache is made.
                        if ( elem && value === undefined ) {

                            // Attempt to get data from the cache
                            // The key will always be camelCased in Data
                            data = dataUser.get( elem, key );
                            if ( data !== undefined ) {
                                return data;
                            }

                            // Attempt to "discover" the data in
                            // HTML5 custom data-* attrs
                            data = dataAttr( elem, key );
                            if ( data !== undefined ) {
                                return data;
                            }

                            // We tried really hard, but the data doesn't exist.
                            return;
                        }

                        // Set the data...
                        this.each( function() {

                            // We always store the camelCased key
                            dataUser.set( this, key, value );
                        } );
                    }, null, value, arguments.length > 1, null, true );
                },

                removeData: function( key ) {
                    return this.each( function() {
                        dataUser.remove( this, key );
                    } );
                }
            } );


            jQuery.extend( {
                queue: function( elem, type, data ) {
                    var queue;

                    if ( elem ) {
                        type = ( type || "fx" ) + "queue";
                        queue = dataPriv.get( elem, type );

                        // Speed up dequeue by getting out quickly if this is just a lookup
                        if ( data ) {
                            if ( !queue || Array.isArray( data ) ) {
                                queue = dataPriv.access( elem, type, jQuery.makeArray( data ) );
                            } else {
                                queue.push( data );
                            }
                        }
                        return queue || [];
                    }
                },

                dequeue: function( elem, type ) {
                    type = type || "fx";

                    var queue = jQuery.queue( elem, type ),
                        startLength = queue.length,
                        fn = queue.shift(),
                        hooks = jQuery._queueHooks( elem, type ),
                        next = function() {
                            jQuery.dequeue( elem, type );
                        };

                    // If the fx queue is dequeued, always remove the progress sentinel
                    if ( fn === "inprogress" ) {
                        fn = queue.shift();
                        startLength--;
                    }

                    if ( fn ) {

                        // Add a progress sentinel to prevent the fx queue from being
                        // automatically dequeued
                        if ( type === "fx" ) {
                            queue.unshift( "inprogress" );
                        }

                        // Clear up the last queue stop function
                        delete hooks.stop;
                        fn.call( elem, next, hooks );
                    }

                    if ( !startLength && hooks ) {
                        hooks.empty.fire();
                    }
                },

                // Not public - generate a queueHooks object, or return the current one
                _queueHooks: function( elem, type ) {
                    var key = type + "queueHooks";
                    return dataPriv.get( elem, key ) || dataPriv.access( elem, key, {
                        empty: jQuery.Callbacks( "once memory" ).add( function() {
                            dataPriv.remove( elem, [ type + "queue", key ] );
                        } )
                    } );
                }
            } );

            jQuery.fn.extend( {
                queue: function( type, data ) {
                    var setter = 2;

                    if ( typeof type !== "string" ) {
                        data = type;
                        type = "fx";
                        setter--;
                    }

                    if ( arguments.length < setter ) {
                        return jQuery.queue( this[ 0 ], type );
                    }

                    return data === undefined ?
                        this :
                        this.each( function() {
                            var queue = jQuery.queue( this, type, data );

                            // Ensure a hooks for this queue
                            jQuery._queueHooks( this, type );

                            if ( type === "fx" && queue[ 0 ] !== "inprogress" ) {
                                jQuery.dequeue( this, type );
                            }
                        } );
                },
                dequeue: function( type ) {
                    return this.each( function() {
                        jQuery.dequeue( this, type );
                    } );
                },
                clearQueue: function( type ) {
                    return this.queue( type || "fx", [] );
                },

                // Get a promise resolved when queues of a certain type
                // are emptied (fx is the type by default)
                promise: function( type, obj ) {
                    var tmp,
                        count = 1,
                        defer = jQuery.Deferred(),
                        elements = this,
                        i = this.length,
                        resolve = function() {
                            if ( !( --count ) ) {
                                defer.resolveWith( elements, [ elements ] );
                            }
                        };

                    if ( typeof type !== "string" ) {
                        obj = type;
                        type = undefined;
                    }
                    type = type || "fx";

                    while ( i-- ) {
                        tmp = dataPriv.get( elements[ i ], type + "queueHooks" );
                        if ( tmp && tmp.empty ) {
                            count++;
                            tmp.empty.add( resolve );
                        }
                    }
                    resolve();
                    return defer.promise( obj );
                }
            } );
            var pnum = ( /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/ ).source;

            var rcssNum = new RegExp( "^(?:([+-])=|)(" + pnum + ")([a-z%]*)$", "i" );


            var cssExpand = [ "Top", "Right", "Bottom", "Left" ];

            var documentElement = document.documentElement;



            var isAttached = function( elem ) {
                    return jQuery.contains( elem.ownerDocument, elem );
                },
                composed = { composed: true };

            // Support: IE 9 - 11+, Edge 12 - 18+, iOS 10.0 - 10.2 only
            // Check attachment across shadow DOM boundaries when possible (gh-3504)
            // Support: iOS 10.0-10.2 only
            // Early iOS 10 versions support `attachShadow` but not `getRootNode`,
            // leading to errors. We need to check for `getRootNode`.
            if ( documentElement.getRootNode ) {
                isAttached = function( elem ) {
                    return jQuery.contains( elem.ownerDocument, elem ) ||
                        elem.getRootNode( composed ) === elem.ownerDocument;
                };
            }
            var isHiddenWithinTree = function( elem, el ) {

                // isHiddenWithinTree might be called from jQuery#filter function;
                // in that case, element will be second argument
                elem = el || elem;

                // Inline style trumps all
                return elem.style.display === "none" ||
                    elem.style.display === "" &&

                    // Otherwise, check computed style
                    // Support: Firefox <=43 - 45
                    // Disconnected elements can have computed display: none, so first confirm that elem is
                    // in the document.
                    isAttached( elem ) &&

                    jQuery.css( elem, "display" ) === "none";
            };



            function adjustCSS( elem, prop, valueParts, tween ) {
                var adjusted, scale,
                    maxIterations = 20,
                    currentValue = tween ?
                        function() {
                            return tween.cur();
                        } :
                        function() {
                            return jQuery.css( elem, prop, "" );
                        },
                    initial = currentValue(),
                    unit = valueParts && valueParts[ 3 ] || ( jQuery.cssNumber[ prop ] ? "" : "px" ),

                    // Starting value computation is required for potential unit mismatches
                    initialInUnit = elem.nodeType &&
                        ( jQuery.cssNumber[ prop ] || unit !== "px" && +initial ) &&
                        rcssNum.exec( jQuery.css( elem, prop ) );

                if ( initialInUnit && initialInUnit[ 3 ] !== unit ) {

                    // Support: Firefox <=54
                    // Halve the iteration target value to prevent interference from CSS upper bounds (gh-2144)
                    initial = initial / 2;

                    // Trust units reported by jQuery.css
                    unit = unit || initialInUnit[ 3 ];

                    // Iteratively approximate from a nonzero starting point
                    initialInUnit = +initial || 1;

                    while ( maxIterations-- ) {

                        // Evaluate and update our best guess (doubling guesses that zero out).
                        // Finish if the scale equals or crosses 1 (making the old*new product non-positive).
                        jQuery.style( elem, prop, initialInUnit + unit );
                        if ( ( 1 - scale ) * ( 1 - ( scale = currentValue() / initial || 0.5 ) ) <= 0 ) {
                            maxIterations = 0;
                        }
                        initialInUnit = initialInUnit / scale;

                    }

                    initialInUnit = initialInUnit * 2;
                    jQuery.style( elem, prop, initialInUnit + unit );

                    // Make sure we update the tween properties later on
                    valueParts = valueParts || [];
                }

                if ( valueParts ) {
                    initialInUnit = +initialInUnit || +initial || 0;

                    // Apply relative offset (+=/-=) if specified
                    adjusted = valueParts[ 1 ] ?
                        initialInUnit + ( valueParts[ 1 ] + 1 ) * valueParts[ 2 ] :
                        +valueParts[ 2 ];
                    if ( tween ) {
                        tween.unit = unit;
                        tween.start = initialInUnit;
                        tween.end = adjusted;
                    }
                }
                return adjusted;
            }


            var defaultDisplayMap = {};

            function getDefaultDisplay( elem ) {
                var temp,
                    doc = elem.ownerDocument,
                    nodeName = elem.nodeName,
                    display = defaultDisplayMap[ nodeName ];

                if ( display ) {
                    return display;
                }

                temp = doc.body.appendChild( doc.createElement( nodeName ) );
                display = jQuery.css( temp, "display" );

                temp.parentNode.removeChild( temp );

                if ( display === "none" ) {
                    display = "block";
                }
                defaultDisplayMap[ nodeName ] = display;

                return display;
            }

            function showHide( elements, show ) {
                var display, elem,
                    values = [],
                    index = 0,
                    length = elements.length;

                // Determine new display value for elements that need to change
                for ( ; index < length; index++ ) {
                    elem = elements[ index ];
                    if ( !elem.style ) {
                        continue;
                    }

                    display = elem.style.display;
                    if ( show ) {

                        // Since we force visibility upon cascade-hidden elements, an immediate (and slow)
                        // check is required in this first loop unless we have a nonempty display value (either
                        // inline or about-to-be-restored)
                        if ( display === "none" ) {
                            values[ index ] = dataPriv.get( elem, "display" ) || null;
                            if ( !values[ index ] ) {
                                elem.style.display = "";
                            }
                        }
                        if ( elem.style.display === "" && isHiddenWithinTree( elem ) ) {
                            values[ index ] = getDefaultDisplay( elem );
                        }
                    } else {
                        if ( display !== "none" ) {
                            values[ index ] = "none";

                            // Remember what we're overwriting
                            dataPriv.set( elem, "display", display );
                        }
                    }
                }

                // Set the display of the elements in a second loop to avoid constant reflow
                for ( index = 0; index < length; index++ ) {
                    if ( values[ index ] != null ) {
                        elements[ index ].style.display = values[ index ];
                    }
                }

                return elements;
            }

            jQuery.fn.extend( {
                show: function() {
                    return showHide( this, true );
                },
                hide: function() {
                    return showHide( this );
                },
                toggle: function( state ) {
                    if ( typeof state === "boolean" ) {
                        return state ? this.show() : this.hide();
                    }

                    return this.each( function() {
                        if ( isHiddenWithinTree( this ) ) {
                            jQuery( this ).show();
                        } else {
                            jQuery( this ).hide();
                        }
                    } );
                }
            } );
            var rcheckableType = ( /^(?:checkbox|radio)$/i );

            var rtagName = ( /<([a-z][^\/\0>\x20\t\r\n\f]*)/i );

            var rscriptType = ( /^$|^module$|\/(?:java|ecma)script/i );



            ( function() {
                var fragment = document.createDocumentFragment(),
                    div = fragment.appendChild( document.createElement( "div" ) ),
                    input = document.createElement( "input" );

                // Support: Android 4.0 - 4.3 only
                // Check state lost if the name is set (#11217)
                // Support: Windows Web Apps (WWA)
                // `name` and `type` must use .setAttribute for WWA (#14901)
                input.setAttribute( "type", "radio" );
                input.setAttribute( "checked", "checked" );
                input.setAttribute( "name", "t" );

                div.appendChild( input );

                // Support: Android <=4.1 only
                // Older WebKit doesn't clone checked state correctly in fragments
                support.checkClone = div.cloneNode( true ).cloneNode( true ).lastChild.checked;

                // Support: IE <=11 only
                // Make sure textarea (and checkbox) defaultValue is properly cloned
                div.innerHTML = "<textarea>x</textarea>";
                support.noCloneChecked = !!div.cloneNode( true ).lastChild.defaultValue;

                // Support: IE <=9 only
                // IE <=9 replaces <option> tags with their contents when inserted outside of
                // the select element.
                div.innerHTML = "<option></option>";
                support.option = !!div.lastChild;
            } )();


// We have to close these tags to support XHTML (#13200)
            var wrapMap = {

                // XHTML parsers do not magically insert elements in the
                // same way that tag soup parsers do. So we cannot shorten
                // this by omitting <tbody> or other required elements.
                thead: [ 1, "<table>", "</table>" ],
                col: [ 2, "<table><colgroup>", "</colgroup></table>" ],
                tr: [ 2, "<table><tbody>", "</tbody></table>" ],
                td: [ 3, "<table><tbody><tr>", "</tr></tbody></table>" ],

                _default: [ 0, "", "" ]
            };

            wrapMap.tbody = wrapMap.tfoot = wrapMap.colgroup = wrapMap.caption = wrapMap.thead;
            wrapMap.th = wrapMap.td;

// Support: IE <=9 only
            if ( !support.option ) {
                wrapMap.optgroup = wrapMap.option = [ 1, "<select multiple='multiple'>", "</select>" ];
            }


            function getAll( context, tag ) {

                // Support: IE <=9 - 11 only
                // Use typeof to avoid zero-argument method invocation on host objects (#15151)
                var ret;

                if ( typeof context.getElementsByTagName !== "undefined" ) {
                    ret = context.getElementsByTagName( tag || "*" );

                } else if ( typeof context.querySelectorAll !== "undefined" ) {
                    ret = context.querySelectorAll( tag || "*" );

                } else {
                    ret = [];
                }

                if ( tag === undefined || tag && nodeName( context, tag ) ) {
                    return jQuery.merge( [ context ], ret );
                }

                return ret;
            }


// Mark scripts as having already been evaluated
            function setGlobalEval( elems, refElements ) {
                var i = 0,
                    l = elems.length;

                for ( ; i < l; i++ ) {
                    dataPriv.set(
                        elems[ i ],
                        "globalEval",
                        !refElements || dataPriv.get( refElements[ i ], "globalEval" )
                    );
                }
            }


            var rhtml = /<|&#?\w+;/;

            function buildFragment( elems, context, scripts, selection, ignored ) {
                var elem, tmp, tag, wrap, attached, j,
                    fragment = context.createDocumentFragment(),
                    nodes = [],
                    i = 0,
                    l = elems.length;

                for ( ; i < l; i++ ) {
                    elem = elems[ i ];

                    if ( elem || elem === 0 ) {

                        // Add nodes directly
                        if ( toType( elem ) === "object" ) {

                            // Support: Android <=4.0 only, PhantomJS 1 only
                            // push.apply(_, arraylike) throws on ancient WebKit
                            jQuery.merge( nodes, elem.nodeType ? [ elem ] : elem );

                            // Convert non-html into a text node
                        } else if ( !rhtml.test( elem ) ) {
                            nodes.push( context.createTextNode( elem ) );

                            // Convert html into DOM nodes
                        } else {
                            tmp = tmp || fragment.appendChild( context.createElement( "div" ) );

                            // Deserialize a standard representation
                            tag = ( rtagName.exec( elem ) || [ "", "" ] )[ 1 ].toLowerCase();
                            wrap = wrapMap[ tag ] || wrapMap._default;
                            tmp.innerHTML = wrap[ 1 ] + jQuery.htmlPrefilter( elem ) + wrap[ 2 ];

                            // Descend through wrappers to the right content
                            j = wrap[ 0 ];
                            while ( j-- ) {
                                tmp = tmp.lastChild;
                            }

                            // Support: Android <=4.0 only, PhantomJS 1 only
                            // push.apply(_, arraylike) throws on ancient WebKit
                            jQuery.merge( nodes, tmp.childNodes );

                            // Remember the top-level container
                            tmp = fragment.firstChild;

                            // Ensure the created nodes are orphaned (#12392)
                            tmp.textContent = "";
                        }
                    }
                }

                // Remove wrapper from fragment
                fragment.textContent = "";

                i = 0;
                while ( ( elem = nodes[ i++ ] ) ) {

                    // Skip elements already in the context collection (trac-4087)
                    if ( selection && jQuery.inArray( elem, selection ) > -1 ) {
                        if ( ignored ) {
                            ignored.push( elem );
                        }
                        continue;
                    }

                    attached = isAttached( elem );

                    // Append to fragment
                    tmp = getAll( fragment.appendChild( elem ), "script" );

                    // Preserve script evaluation history
                    if ( attached ) {
                        setGlobalEval( tmp );
                    }

                    // Capture executables
                    if ( scripts ) {
                        j = 0;
                        while ( ( elem = tmp[ j++ ] ) ) {
                            if ( rscriptType.test( elem.type || "" ) ) {
                                scripts.push( elem );
                            }
                        }
                    }
                }

                return fragment;
            }


            var
                rkeyEvent = /^key/,
                rmouseEvent = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
                rtypenamespace = /^([^.]*)(?:\.(.+)|)/;

            function returnTrue() {
                return true;
            }

            function returnFalse() {
                return false;
            }

// Support: IE <=9 - 11+
// focus() and blur() are asynchronous, except when they are no-op.
// So expect focus to be synchronous when the element is already active,
// and blur to be synchronous when the element is not already active.
// (focus and blur are always synchronous in other supported browsers,
// this just defines when we can count on it).
            function expectSync( elem, type ) {
                return ( elem === safeActiveElement() ) === ( type === "focus" );
            }

// Support: IE <=9 only
// Accessing document.activeElement can throw unexpectedly
// https://bugs.jquery.com/ticket/13393
            function safeActiveElement() {
                try {
                    return document.activeElement;
                } catch ( err ) { }
            }

            function on( elem, types, selector, data, fn, one ) {
                var origFn, type;

                // Types can be a map of types/handlers
                if ( typeof types === "object" ) {

                    // ( types-Object, selector, data )
                    if ( typeof selector !== "string" ) {

                        // ( types-Object, data )
                        data = data || selector;
                        selector = undefined;
                    }
                    for ( type in types ) {
                        on( elem, type, selector, data, types[ type ], one );
                    }
                    return elem;
                }

                if ( data == null && fn == null ) {

                    // ( types, fn )
                    fn = selector;
                    data = selector = undefined;
                } else if ( fn == null ) {
                    if ( typeof selector === "string" ) {

                        // ( types, selector, fn )
                        fn = data;
                        data = undefined;
                    } else {

                        // ( types, data, fn )
                        fn = data;
                        data = selector;
                        selector = undefined;
                    }
                }
                if ( fn === false ) {
                    fn = returnFalse;
                } else if ( !fn ) {
                    return elem;
                }

                if ( one === 1 ) {
                    origFn = fn;
                    fn = function( event ) {

                        // Can use an empty set, since event contains the info
                        jQuery().off( event );
                        return origFn.apply( this, arguments );
                    };

                    // Use same guid so caller can remove using origFn
                    fn.guid = origFn.guid || ( origFn.guid = jQuery.guid++ );
                }
                return elem.each( function() {
                    jQuery.event.add( this, types, fn, data, selector );
                } );
            }

            /*
 * Helper functions for managing events -- not part of the public interface.
 * Props to Dean Edwards' addEvent library for many of the ideas.
 */
            jQuery.event = {

                global: {},

                add: function( elem, types, handler, data, selector ) {

                    var handleObjIn, eventHandle, tmp,
                        events, t, handleObj,
                        special, handlers, type, namespaces, origType,
                        elemData = dataPriv.get( elem );

                    // Only attach events to objects that accept data
                    if ( !acceptData( elem ) ) {
                        return;
                    }

                    // Caller can pass in an object of custom data in lieu of the handler
                    if ( handler.handler ) {
                        handleObjIn = handler;
                        handler = handleObjIn.handler;
                        selector = handleObjIn.selector;
                    }

                    // Ensure that invalid selectors throw exceptions at attach time
                    // Evaluate against documentElement in case elem is a non-element node (e.g., document)
                    if ( selector ) {
                        jQuery.find.matchesSelector( documentElement, selector );
                    }

                    // Make sure that the handler has a unique ID, used to find/remove it later
                    if ( !handler.guid ) {
                        handler.guid = jQuery.guid++;
                    }

                    // Init the element's event structure and main handler, if this is the first
                    if ( !( events = elemData.events ) ) {
                        events = elemData.events = Object.create( null );
                    }
                    if ( !( eventHandle = elemData.handle ) ) {
                        eventHandle = elemData.handle = function( e ) {

                            // Discard the second event of a jQuery.event.trigger() and
                            // when an event is called after a page has unloaded
                            return typeof jQuery !== "undefined" && jQuery.event.triggered !== e.type ?
                                jQuery.event.dispatch.apply( elem, arguments ) : undefined;
                        };
                    }

                    // Handle multiple events separated by a space
                    types = ( types || "" ).match( rnothtmlwhite ) || [ "" ];
                    t = types.length;
                    while ( t-- ) {
                        tmp = rtypenamespace.exec( types[ t ] ) || [];
                        type = origType = tmp[ 1 ];
                        namespaces = ( tmp[ 2 ] || "" ).split( "." ).sort();

                        // There *must* be a type, no attaching namespace-only handlers
                        if ( !type ) {
                            continue;
                        }

                        // If event changes its type, use the special event handlers for the changed type
                        special = jQuery.event.special[ type ] || {};

                        // If selector defined, determine special event api type, otherwise given type
                        type = ( selector ? special.delegateType : special.bindType ) || type;

                        // Update special based on newly reset type
                        special = jQuery.event.special[ type ] || {};

                        // handleObj is passed to all event handlers
                        handleObj = jQuery.extend( {
                            type: type,
                            origType: origType,
                            data: data,
                            handler: handler,
                            guid: handler.guid,
                            selector: selector,
                            needsContext: selector && jQuery.expr.match.needsContext.test( selector ),
                            namespace: namespaces.join( "." )
                        }, handleObjIn );

                        // Init the event handler queue if we're the first
                        if ( !( handlers = events[ type ] ) ) {
                            handlers = events[ type ] = [];
                            handlers.delegateCount = 0;

                            // Only use addEventListener if the special events handler returns false
                            if ( !special.setup ||
                                special.setup.call( elem, data, namespaces, eventHandle ) === false ) {

                                if ( elem.addEventListener ) {
                                    elem.addEventListener( type, eventHandle );
                                }
                            }
                        }

                        if ( special.add ) {
                            special.add.call( elem, handleObj );

                            if ( !handleObj.handler.guid ) {
                                handleObj.handler.guid = handler.guid;
                            }
                        }

                        // Add to the element's handler list, delegates in front
                        if ( selector ) {
                            handlers.splice( handlers.delegateCount++, 0, handleObj );
                        } else {
                            handlers.push( handleObj );
                        }

                        // Keep track of which events have ever been used, for event optimization
                        jQuery.event.global[ type ] = true;
                    }

                },

                // Detach an event or set of events from an element
                remove: function( elem, types, handler, selector, mappedTypes ) {

                    var j, origCount, tmp,
                        events, t, handleObj,
                        special, handlers, type, namespaces, origType,
                        elemData = dataPriv.hasData( elem ) && dataPriv.get( elem );

                    if ( !elemData || !( events = elemData.events ) ) {
                        return;
                    }

                    // Once for each type.namespace in types; type may be omitted
                    types = ( types || "" ).match( rnothtmlwhite ) || [ "" ];
                    t = types.length;
                    while ( t-- ) {
                        tmp = rtypenamespace.exec( types[ t ] ) || [];
                        type = origType = tmp[ 1 ];
                        namespaces = ( tmp[ 2 ] || "" ).split( "." ).sort();

                        // Unbind all events (on this namespace, if provided) for the element
                        if ( !type ) {
                            for ( type in events ) {
                                jQuery.event.remove( elem, type + types[ t ], handler, selector, true );
                            }
                            continue;
                        }

                        special = jQuery.event.special[ type ] || {};
                        type = ( selector ? special.delegateType : special.bindType ) || type;
                        handlers = events[ type ] || [];
                        tmp = tmp[ 2 ] &&
                            new RegExp( "(^|\\.)" + namespaces.join( "\\.(?:.*\\.|)" ) + "(\\.|$)" );

                        // Remove matching events
                        origCount = j = handlers.length;
                        while ( j-- ) {
                            handleObj = handlers[ j ];

                            if ( ( mappedTypes || origType === handleObj.origType ) &&
                                ( !handler || handler.guid === handleObj.guid ) &&
                                ( !tmp || tmp.test( handleObj.namespace ) ) &&
                                ( !selector || selector === handleObj.selector ||
                                    selector === "**" && handleObj.selector ) ) {
                                handlers.splice( j, 1 );

                                if ( handleObj.selector ) {
                                    handlers.delegateCount--;
                                }
                                if ( special.remove ) {
                                    special.remove.call( elem, handleObj );
                                }
                            }
                        }

                        // Remove generic event handler if we removed something and no more handlers exist
                        // (avoids potential for endless recursion during removal of special event handlers)
                        if ( origCount && !handlers.length ) {
                            if ( !special.teardown ||
                                special.teardown.call( elem, namespaces, elemData.handle ) === false ) {

                                jQuery.removeEvent( elem, type, elemData.handle );
                            }

                            delete events[ type ];
                        }
                    }

                    // Remove data and the expando if it's no longer used
                    if ( jQuery.isEmptyObject( events ) ) {
                        dataPriv.remove( elem, "handle events" );
                    }
                },

                dispatch: function( nativeEvent ) {

                    var i, j, ret, matched, handleObj, handlerQueue,
                        args = new Array( arguments.length ),

                        // Make a writable jQuery.Event from the native event object
                        event = jQuery.event.fix( nativeEvent ),

                        handlers = (
                            dataPriv.get( this, "events" ) || Object.create( null )
                        )[ event.type ] || [],
                        special = jQuery.event.special[ event.type ] || {};

                    // Use the fix-ed jQuery.Event rather than the (read-only) native event
                    args[ 0 ] = event;

                    for ( i = 1; i < arguments.length; i++ ) {
                        args[ i ] = arguments[ i ];
                    }

                    event.delegateTarget = this;

                    // Call the preDispatch hook for the mapped type, and let it bail if desired
                    if ( special.preDispatch && special.preDispatch.call( this, event ) === false ) {
                        return;
                    }

                    // Determine handlers
                    handlerQueue = jQuery.event.handlers.call( this, event, handlers );

                    // Run delegates first; they may want to stop propagation beneath us
                    i = 0;
                    while ( ( matched = handlerQueue[ i++ ] ) && !event.isPropagationStopped() ) {
                        event.currentTarget = matched.elem;

                        j = 0;
                        while ( ( handleObj = matched.handlers[ j++ ] ) &&
                        !event.isImmediatePropagationStopped() ) {

                            // If the event is namespaced, then each handler is only invoked if it is
                            // specially universal or its namespaces are a superset of the event's.
                            if ( !event.rnamespace || handleObj.namespace === false ||
                                event.rnamespace.test( handleObj.namespace ) ) {

                                event.handleObj = handleObj;
                                event.data = handleObj.data;

                                ret = ( ( jQuery.event.special[ handleObj.origType ] || {} ).handle ||
                                    handleObj.handler ).apply( matched.elem, args );

                                if ( ret !== undefined ) {
                                    if ( ( event.result = ret ) === false ) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                }
                            }
                        }
                    }

                    // Call the postDispatch hook for the mapped type
                    if ( special.postDispatch ) {
                        special.postDispatch.call( this, event );
                    }

                    return event.result;
                },

                handlers: function( event, handlers ) {
                    var i, handleObj, sel, matchedHandlers, matchedSelectors,
                        handlerQueue = [],
                        delegateCount = handlers.delegateCount,
                        cur = event.target;

                    // Find delegate handlers
                    if ( delegateCount &&

                        // Support: IE <=9
                        // Black-hole SVG <use> instance trees (trac-13180)
                        cur.nodeType &&

                        // Support: Firefox <=42
                        // Suppress spec-violating clicks indicating a non-primary pointer button (trac-3861)
                        // https://www.w3.org/TR/DOM-Level-3-Events/#event-type-click
                        // Support: IE 11 only
                        // ...but not arrow key "clicks" of radio inputs, which can have `button` -1 (gh-2343)
                        !( event.type === "click" && event.button >= 1 ) ) {

                        for ( ; cur !== this; cur = cur.parentNode || this ) {

                            // Don't check non-elements (#13208)
                            // Don't process clicks on disabled elements (#6911, #8165, #11382, #11764)
                            if ( cur.nodeType === 1 && !( event.type === "click" && cur.disabled === true ) ) {
                                matchedHandlers = [];
                                matchedSelectors = {};
                                for ( i = 0; i < delegateCount; i++ ) {
                                    handleObj = handlers[ i ];

                                    // Don't conflict with Object.prototype properties (#13203)
                                    sel = handleObj.selector + " ";

                                    if ( matchedSelectors[ sel ] === undefined ) {
                                        matchedSelectors[ sel ] = handleObj.needsContext ?
                                            jQuery( sel, this ).index( cur ) > -1 :
                                            jQuery.find( sel, this, null, [ cur ] ).length;
                                    }
                                    if ( matchedSelectors[ sel ] ) {
                                        matchedHandlers.push( handleObj );
                                    }
                                }
                                if ( matchedHandlers.length ) {
                                    handlerQueue.push( { elem: cur, handlers: matchedHandlers } );
                                }
                            }
                        }
                    }

                    // Add the remaining (directly-bound) handlers
                    cur = this;
                    if ( delegateCount < handlers.length ) {
                        handlerQueue.push( { elem: cur, handlers: handlers.slice( delegateCount ) } );
                    }

                    return handlerQueue;
                },

                addProp: function( name, hook ) {
                    Object.defineProperty( jQuery.Event.prototype, name, {
                        enumerable: true,
                        configurable: true,

                        get: isFunction( hook ) ?
                            function() {
                                if ( this.originalEvent ) {
                                    return hook( this.originalEvent );
                                }
                            } :
                            function() {
                                if ( this.originalEvent ) {
                                    return this.originalEvent[ name ];
                                }
                            },

                        set: function( value ) {
                            Object.defineProperty( this, name, {
                                enumerable: true,
                                configurable: true,
                                writable: true,
                                value: value
                            } );
                        }
                    } );
                },

                fix: function( originalEvent ) {
                    return originalEvent[ jQuery.expando ] ?
                        originalEvent :
                        new jQuery.Event( originalEvent );
                },

                special: {
                    load: {

                        // Prevent triggered image.load events from bubbling to window.load
                        noBubble: true
                    },
                    click: {

                        // Utilize native event to ensure correct state for checkable inputs
                        setup: function( data ) {

                            // For mutual compressibility with _default, replace `this` access with a local var.
                            // `|| data` is dead code meant only to preserve the variable through minification.
                            var el = this || data;

                            // Claim the first handler
                            if ( rcheckableType.test( el.type ) &&
                                el.click && nodeName( el, "input" ) ) {

                                // dataPriv.set( el, "click", ... )
                                leverageNative( el, "click", returnTrue );
                            }

                            // Return false to allow normal processing in the caller
                            return false;
                        },
                        trigger: function( data ) {

                            // For mutual compressibility with _default, replace `this` access with a local var.
                            // `|| data` is dead code meant only to preserve the variable through minification.
                            var el = this || data;

                            // Force setup before triggering a click
                            if ( rcheckableType.test( el.type ) &&
                                el.click && nodeName( el, "input" ) ) {

                                leverageNative( el, "click" );
                            }

                            // Return non-false to allow normal event-path propagation
                            return true;
                        },

                        // For cross-browser consistency, suppress native .click() on links
                        // Also prevent it if we're currently inside a leveraged native-event stack
                        _default: function( event ) {
                            var target = event.target;
                            return rcheckableType.test( target.type ) &&
                                target.click && nodeName( target, "input" ) &&
                                dataPriv.get( target, "click" ) ||
                                nodeName( target, "a" );
                        }
                    },

                    beforeunload: {
                        postDispatch: function( event ) {

                            // Support: Firefox 20+
                            // Firefox doesn't alert if the returnValue field is not set.
                            if ( event.result !== undefined && event.originalEvent ) {
                                event.originalEvent.returnValue = event.result;
                            }
                        }
                    }
                }
            };

// Ensure the presence of an event listener that handles manually-triggered
// synthetic events by interrupting progress until reinvoked in response to
// *native* events that it fires directly, ensuring that state changes have
// already occurred before other listeners are invoked.
            function leverageNative( el, type, expectSync ) {

                // Missing expectSync indicates a trigger call, which must force setup through jQuery.event.add
                if ( !expectSync ) {
                    if ( dataPriv.get( el, type ) === undefined ) {
                        jQuery.event.add( el, type, returnTrue );
                    }
                    return;
                }

                // Register the controller as a special universal handler for all event namespaces
                dataPriv.set( el, type, false );
                jQuery.event.add( el, type, {
                    namespace: false,
                    handler: function( event ) {
                        var notAsync, result,
                            saved = dataPriv.get( this, type );

                        if ( ( event.isTrigger & 1 ) && this[ type ] ) {

                            // Interrupt processing of the outer synthetic .trigger()ed event
                            // Saved data should be false in such cases, but might be a leftover capture object
                            // from an async native handler (gh-4350)
                            if ( !saved.length ) {

                                // Store arguments for use when handling the inner native event
                                // There will always be at least one argument (an event object), so this array
                                // will not be confused with a leftover capture object.
                                saved = slice.call( arguments );
                                dataPriv.set( this, type, saved );

                                // Trigger the native event and capture its result
                                // Support: IE <=9 - 11+
                                // focus() and blur() are asynchronous
                                notAsync = expectSync( this, type );
                                this[ type ]();
                                result = dataPriv.get( this, type );
                                if ( saved !== result || notAsync ) {
                                    dataPriv.set( this, type, false );
                                } else {
                                    result = {};
                                }
                                if ( saved !== result ) {

                                    // Cancel the outer synthetic event
                                    event.stopImmediatePropagation();
                                    event.preventDefault();
                                    return result.value;
                                }

                                // If this is an inner synthetic event for an event with a bubbling surrogate
                                // (focus or blur), assume that the surrogate already propagated from triggering the
                                // native event and prevent that from happening again here.
                                // This technically gets the ordering wrong w.r.t. to `.trigger()` (in which the
                                // bubbling surrogate propagates *after* the non-bubbling base), but that seems
                                // less bad than duplication.
                            } else if ( ( jQuery.event.special[ type ] || {} ).delegateType ) {
                                event.stopPropagation();
                            }

                            // If this is a native event triggered above, everything is now in order
                            // Fire an inner synthetic event with the original arguments
                        } else if ( saved.length ) {

                            // ...and capture the result
                            dataPriv.set( this, type, {
                                value: jQuery.event.trigger(

                                    // Support: IE <=9 - 11+
                                    // Extend with the prototype to reset the above stopImmediatePropagation()
                                    jQuery.extend( saved[ 0 ], jQuery.Event.prototype ),
                                    saved.slice( 1 ),
                                    this
                                )
                            } );

                            // Abort handling of the native event
                            event.stopImmediatePropagation();
                        }
                    }
                } );
            }

            jQuery.removeEvent = function( elem, type, handle ) {

                // This "if" is needed for plain objects
                if ( elem.removeEventListener ) {
                    elem.removeEventListener( type, handle );
                }
            };

            jQuery.Event = function( src, props ) {

                // Allow instantiation without the 'new' keyword
                if ( !( this instanceof jQuery.Event ) ) {
                    return new jQuery.Event( src, props );
                }

                // Event object
                if ( src && src.type ) {
                    this.originalEvent = src;
                    this.type = src.type;

                    // Events bubbling up the document may have been marked as prevented
                    // by a handler lower down the tree; reflect the correct value.
                    this.isDefaultPrevented = src.defaultPrevented ||
                    src.defaultPrevented === undefined &&

                    // Support: Android <=2.3 only
                    src.returnValue === false ?
                        returnTrue :
                        returnFalse;

                    // Create target properties
                    // Support: Safari <=6 - 7 only
                    // Target should not be a text node (#504, #13143)
                    this.target = ( src.target && src.target.nodeType === 3 ) ?
                        src.target.parentNode :
                        src.target;

                    this.currentTarget = src.currentTarget;
                    this.relatedTarget = src.relatedTarget;

                    // Event type
                } else {
                    this.type = src;
                }

                // Put explicitly provided properties onto the event object
                if ( props ) {
                    jQuery.extend( this, props );
                }

                // Create a timestamp if incoming event doesn't have one
                this.timeStamp = src && src.timeStamp || Date.now();

                // Mark it as fixed
                this[ jQuery.expando ] = true;
            };

// jQuery.Event is based on DOM3 Events as specified by the ECMAScript Language Binding
// https://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
            jQuery.Event.prototype = {
                constructor: jQuery.Event,
                isDefaultPrevented: returnFalse,
                isPropagationStopped: returnFalse,
                isImmediatePropagationStopped: returnFalse,
                isSimulated: false,

                preventDefault: function() {
                    var e = this.originalEvent;

                    this.isDefaultPrevented = returnTrue;

                    if ( e && !this.isSimulated ) {
                        e.preventDefault();
                    }
                },
                stopPropagation: function() {
                    var e = this.originalEvent;

                    this.isPropagationStopped = returnTrue;

                    if ( e && !this.isSimulated ) {
                        e.stopPropagation();
                    }
                },
                stopImmediatePropagation: function() {
                    var e = this.originalEvent;

                    this.isImmediatePropagationStopped = returnTrue;

                    if ( e && !this.isSimulated ) {
                        e.stopImmediatePropagation();
                    }

                    this.stopPropagation();
                }
            };

// Includes all common event props including KeyEvent and MouseEvent specific props
            jQuery.each( {
                altKey: true,
                bubbles: true,
                cancelable: true,
                changedTouches: true,
                ctrlKey: true,
                detail: true,
                eventPhase: true,
                metaKey: true,
                pageX: true,
                pageY: true,
                shiftKey: true,
                view: true,
                "char": true,
                code: true,
                charCode: true,
                key: true,
                keyCode: true,
                button: true,
                buttons: true,
                clientX: true,
                clientY: true,
                offsetX: true,
                offsetY: true,
                pointerId: true,
                pointerType: true,
                screenX: true,
                screenY: true,
                targetTouches: true,
                toElement: true,
                touches: true,

                which: function( event ) {
                    var button = event.button;

                    // Add which for key events
                    if ( event.which == null && rkeyEvent.test( event.type ) ) {
                        return event.charCode != null ? event.charCode : event.keyCode;
                    }

                    // Add which for click: 1 === left; 2 === middle; 3 === right
                    if ( !event.which && button !== undefined && rmouseEvent.test( event.type ) ) {
                        if ( button & 1 ) {
                            return 1;
                        }

                        if ( button & 2 ) {
                            return 3;
                        }

                        if ( button & 4 ) {
                            return 2;
                        }

                        return 0;
                    }

                    return event.which;
                }
            }, jQuery.event.addProp );

            jQuery.each( { focus: "focusin", blur: "focusout" }, function( type, delegateType ) {
                jQuery.event.special[ type ] = {

                    // Utilize native event if possible so blur/focus sequence is correct
                    setup: function() {

                        // Claim the first handler
                        // dataPriv.set( this, "focus", ... )
                        // dataPriv.set( this, "blur", ... )
                        leverageNative( this, type, expectSync );

                        // Return false to allow normal processing in the caller
                        return false;
                    },
                    trigger: function() {

                        // Force setup before trigger
                        leverageNative( this, type );

                        // Return non-false to allow normal event-path propagation
                        return true;
                    },

                    delegateType: delegateType
                };
            } );

// Create mouseenter/leave events using mouseover/out and event-time checks
// so that event delegation works in jQuery.
// Do the same for pointerenter/pointerleave and pointerover/pointerout
//
// Support: Safari 7 only
// Safari sends mouseenter too often; see:
// https://bugs.chromium.org/p/chromium/issues/detail?id=470258
// for the description of the bug (it existed in older Chrome versions as well).
            jQuery.each( {
                mouseenter: "mouseover",
                mouseleave: "mouseout",
                pointerenter: "pointerover",
                pointerleave: "pointerout"
            }, function( orig, fix ) {
                jQuery.event.special[ orig ] = {
                    delegateType: fix,
                    bindType: fix,

                    handle: function( event ) {
                        var ret,
                            target = this,
                            related = event.relatedTarget,
                            handleObj = event.handleObj;

                        // For mouseenter/leave call the handler if related is outside the target.
                        // NB: No relatedTarget if the mouse left/entered the browser window
                        if ( !related || ( related !== target && !jQuery.contains( target, related ) ) ) {
                            event.type = handleObj.origType;
                            ret = handleObj.handler.apply( this, arguments );
                            event.type = fix;
                        }
                        return ret;
                    }
                };
            } );

            jQuery.fn.extend( {

                on: function( types, selector, data, fn ) {
                    return on( this, types, selector, data, fn );
                },
                one: function( types, selector, data, fn ) {
                    return on( this, types, selector, data, fn, 1 );
                },
                off: function( types, selector, fn ) {
                    var handleObj, type;
                    if ( types && types.preventDefault && types.handleObj ) {

                        // ( event )  dispatched jQuery.Event
                        handleObj = types.handleObj;
                        jQuery( types.delegateTarget ).off(
                            handleObj.namespace ?
                                handleObj.origType + "." + handleObj.namespace :
                                handleObj.origType,
                            handleObj.selector,
                            handleObj.handler
                        );
                        return this;
                    }
                    if ( typeof types === "object" ) {

                        // ( types-object [, selector] )
                        for ( type in types ) {
                            this.off( type, selector, types[ type ] );
                        }
                        return this;
                    }
                    if ( selector === false || typeof selector === "function" ) {

                        // ( types [, fn] )
                        fn = selector;
                        selector = undefined;
                    }
                    if ( fn === false ) {
                        fn = returnFalse;
                    }
                    return this.each( function() {
                        jQuery.event.remove( this, types, fn, selector );
                    } );
                }
            } );


            var

                // Support: IE <=10 - 11, Edge 12 - 13 only
                // In IE/Edge using regex groups here causes severe slowdowns.
                // See https://connect.microsoft.com/IE/feedback/details/1736512/
                rnoInnerhtml = /<script|<style|<link/i,

                // checked="checked" or checked
                rchecked = /checked\s*(?:[^=]|=\s*.checked.)/i,
                rcleanScript = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;

// Prefer a tbody over its parent table for containing new rows
            function manipulationTarget( elem, content ) {
                if ( nodeName( elem, "table" ) &&
                    nodeName( content.nodeType !== 11 ? content : content.firstChild, "tr" ) ) {

                    return jQuery( elem ).children( "tbody" )[ 0 ] || elem;
                }

                return elem;
            }

// Replace/restore the type attribute of script elements for safe DOM manipulation
            function disableScript( elem ) {
                elem.type = ( elem.getAttribute( "type" ) !== null ) + "/" + elem.type;
                return elem;
            }
            function restoreScript( elem ) {
                if ( ( elem.type || "" ).slice( 0, 5 ) === "true/" ) {
                    elem.type = elem.type.slice( 5 );
                } else {
                    elem.removeAttribute( "type" );
                }

                return elem;
            }

            function cloneCopyEvent( src, dest ) {
                var i, l, type, pdataOld, udataOld, udataCur, events;

                if ( dest.nodeType !== 1 ) {
                    return;
                }

                // 1. Copy private data: events, handlers, etc.
                if ( dataPriv.hasData( src ) ) {
                    pdataOld = dataPriv.get( src );
                    events = pdataOld.events;

                    if ( events ) {
                        dataPriv.remove( dest, "handle events" );

                        for ( type in events ) {
                            for ( i = 0, l = events[ type ].length; i < l; i++ ) {
                                jQuery.event.add( dest, type, events[ type ][ i ] );
                            }
                        }
                    }
                }

                // 2. Copy user data
                if ( dataUser.hasData( src ) ) {
                    udataOld = dataUser.access( src );
                    udataCur = jQuery.extend( {}, udataOld );

                    dataUser.set( dest, udataCur );
                }
            }

// Fix IE bugs, see support tests
            function fixInput( src, dest ) {
                var nodeName = dest.nodeName.toLowerCase();

                // Fails to persist the checked state of a cloned checkbox or radio button.
                if ( nodeName === "input" && rcheckableType.test( src.type ) ) {
                    dest.checked = src.checked;

                    // Fails to return the selected option to the default selected state when cloning options
                } else if ( nodeName === "input" || nodeName === "textarea" ) {
                    dest.defaultValue = src.defaultValue;
                }
            }

            function domManip( collection, args, callback, ignored ) {

                // Flatten any nested arrays
                args = flat( args );

                var fragment, first, scripts, hasScripts, node, doc,
                    i = 0,
                    l = collection.length,
                    iNoClone = l - 1,
                    value = args[ 0 ],
                    valueIsFunction = isFunction( value );

                // We can't cloneNode fragments that contain checked, in WebKit
                if ( valueIsFunction ||
                    ( l > 1 && typeof value === "string" &&
                        !support.checkClone && rchecked.test( value ) ) ) {
                    return collection.each( function( index ) {
                        var self = collection.eq( index );
                        if ( valueIsFunction ) {
                            args[ 0 ] = value.call( this, index, self.html() );
                        }
                        domManip( self, args, callback, ignored );
                    } );
                }

                if ( l ) {
                    fragment = buildFragment( args, collection[ 0 ].ownerDocument, false, collection, ignored );
                    first = fragment.firstChild;

                    if ( fragment.childNodes.length === 1 ) {
                        fragment = first;
                    }

                    // Require either new content or an interest in ignored elements to invoke the callback
                    if ( first || ignored ) {
                        scripts = jQuery.map( getAll( fragment, "script" ), disableScript );
                        hasScripts = scripts.length;

                        // Use the original fragment for the last item
                        // instead of the first because it can end up
                        // being emptied incorrectly in certain situations (#8070).
                        for ( ; i < l; i++ ) {
                            node = fragment;

                            if ( i !== iNoClone ) {
                                node = jQuery.clone( node, true, true );

                                // Keep references to cloned scripts for later restoration
                                if ( hasScripts ) {

                                    // Support: Android <=4.0 only, PhantomJS 1 only
                                    // push.apply(_, arraylike) throws on ancient WebKit
                                    jQuery.merge( scripts, getAll( node, "script" ) );
                                }
                            }

                            callback.call( collection[ i ], node, i );
                        }

                        if ( hasScripts ) {
                            doc = scripts[ scripts.length - 1 ].ownerDocument;

                            // Reenable scripts
                            jQuery.map( scripts, restoreScript );

                            // Evaluate executable scripts on first document insertion
                            for ( i = 0; i < hasScripts; i++ ) {
                                node = scripts[ i ];
                                if ( rscriptType.test( node.type || "" ) &&
                                    !dataPriv.access( node, "globalEval" ) &&
                                    jQuery.contains( doc, node ) ) {

                                    if ( node.src && ( node.type || "" ).toLowerCase()  !== "module" ) {

                                        // Optional AJAX dependency, but won't run scripts if not present
                                        if ( jQuery._evalUrl && !node.noModule ) {
                                            jQuery._evalUrl( node.src, {
                                                nonce: node.nonce || node.getAttribute( "nonce" )
                                            }, doc );
                                        }
                                    } else {
                                        DOMEval( node.textContent.replace( rcleanScript, "" ), node, doc );
                                    }
                                }
                            }
                        }
                    }
                }

                return collection;
            }

            function remove( elem, selector, keepData ) {
                var node,
                    nodes = selector ? jQuery.filter( selector, elem ) : elem,
                    i = 0;

                for ( ; ( node = nodes[ i ] ) != null; i++ ) {
                    if ( !keepData && node.nodeType === 1 ) {
                        jQuery.cleanData( getAll( node ) );
                    }

                    if ( node.parentNode ) {
                        if ( keepData && isAttached( node ) ) {
                            setGlobalEval( getAll( node, "script" ) );
                        }
                        node.parentNode.removeChild( node );
                    }
                }

                return elem;
            }

            jQuery.extend( {
                htmlPrefilter: function( html ) {
                    return html;
                },

                clone: function( elem, dataAndEvents, deepDataAndEvents ) {
                    var i, l, srcElements, destElements,
                        clone = elem.cloneNode( true ),
                        inPage = isAttached( elem );

                    // Fix IE cloning issues
                    if ( !support.noCloneChecked && ( elem.nodeType === 1 || elem.nodeType === 11 ) &&
                        !jQuery.isXMLDoc( elem ) ) {

                        // We eschew Sizzle here for performance reasons: https://jsperf.com/getall-vs-sizzle/2
                        destElements = getAll( clone );
                        srcElements = getAll( elem );

                        for ( i = 0, l = srcElements.length; i < l; i++ ) {
                            fixInput( srcElements[ i ], destElements[ i ] );
                        }
                    }

                    // Copy the events from the original to the clone
                    if ( dataAndEvents ) {
                        if ( deepDataAndEvents ) {
                            srcElements = srcElements || getAll( elem );
                            destElements = destElements || getAll( clone );

                            for ( i = 0, l = srcElements.length; i < l; i++ ) {
                                cloneCopyEvent( srcElements[ i ], destElements[ i ] );
                            }
                        } else {
                            cloneCopyEvent( elem, clone );
                        }
                    }

                    // Preserve script evaluation history
                    destElements = getAll( clone, "script" );
                    if ( destElements.length > 0 ) {
                        setGlobalEval( destElements, !inPage && getAll( elem, "script" ) );
                    }

                    // Return the cloned set
                    return clone;
                },

                cleanData: function( elems ) {
                    var data, elem, type,
                        special = jQuery.event.special,
                        i = 0;

                    for ( ; ( elem = elems[ i ] ) !== undefined; i++ ) {
                        if ( acceptData( elem ) ) {
                            if ( ( data = elem[ dataPriv.expando ] ) ) {
                                if ( data.events ) {
                                    for ( type in data.events ) {
                                        if ( special[ type ] ) {
                                            jQuery.event.remove( elem, type );

                                            // This is a shortcut to avoid jQuery.event.remove's overhead
                                        } else {
                                            jQuery.removeEvent( elem, type, data.handle );
                                        }
                                    }
                                }

                                // Support: Chrome <=35 - 45+
                                // Assign undefined instead of using delete, see Data#remove
                                elem[ dataPriv.expando ] = undefined;
                            }
                            if ( elem[ dataUser.expando ] ) {

                                // Support: Chrome <=35 - 45+
                                // Assign undefined instead of using delete, see Data#remove
                                elem[ dataUser.expando ] = undefined;
                            }
                        }
                    }
                }
            } );

            jQuery.fn.extend( {
                detach: function( selector ) {
                    return remove( this, selector, true );
                },

                remove: function( selector ) {
                    return remove( this, selector );
                },

                text: function( value ) {
                    return access( this, function( value ) {
                        return value === undefined ?
                            jQuery.text( this ) :
                            this.empty().each( function() {
                                if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
                                    this.textContent = value;
                                }
                            } );
                    }, null, value, arguments.length );
                },

                append: function() {
                    return domManip( this, arguments, function( elem ) {
                        if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
                            var target = manipulationTarget( this, elem );
                            target.appendChild( elem );
                        }
                    } );
                },

                prepend: function() {
                    return domManip( this, arguments, function( elem ) {
                        if ( this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9 ) {
                            var target = manipulationTarget( this, elem );
                            target.insertBefore( elem, target.firstChild );
                        }
                    } );
                },

                before: function() {
                    return domManip( this, arguments, function( elem ) {
                        if ( this.parentNode ) {
                            this.parentNode.insertBefore( elem, this );
                        }
                    } );
                },

                after: function() {
                    return domManip( this, arguments, function( elem ) {
                        if ( this.parentNode ) {
                            this.parentNode.insertBefore( elem, this.nextSibling );
                        }
                    } );
                },

                empty: function() {
                    var elem,
                        i = 0;

                    for ( ; ( elem = this[ i ] ) != null; i++ ) {
                        if ( elem.nodeType === 1 ) {

                            // Prevent memory leaks
                            jQuery.cleanData( getAll( elem, false ) );

                            // Remove any remaining nodes
                            elem.textContent = "";
                        }
                    }

                    return this;
                },

                clone: function( dataAndEvents, deepDataAndEvents ) {
                    dataAndEvents = dataAndEvents == null ? false : dataAndEvents;
                    deepDataAndEvents = deepDataAndEvents == null ? dataAndEvents : deepDataAndEvents;

                    return this.map( function() {
                        return jQuery.clone( this, dataAndEvents, deepDataAndEvents );
                    } );
                },

                html: function( value ) {
                    return access( this, function( value ) {
                        var elem = this[ 0 ] || {},
                            i = 0,
                            l = this.length;

                        if ( value === undefined && elem.nodeType === 1 ) {
                            return elem.innerHTML;
                        }

                        // See if we can take a shortcut and just use innerHTML
                        if ( typeof value === "string" && !rnoInnerhtml.test( value ) &&
                            !wrapMap[ ( rtagName.exec( value ) || [ "", "" ] )[ 1 ].toLowerCase() ] ) {

                            value = jQuery.htmlPrefilter( value );

                            try {
                                for ( ; i < l; i++ ) {
                                    elem = this[ i ] || {};

                                    // Remove element nodes and prevent memory leaks
                                    if ( elem.nodeType === 1 ) {
                                        jQuery.cleanData( getAll( elem, false ) );
                                        elem.innerHTML = value;
                                    }
                                }

                                elem = 0;

                                // If using innerHTML throws an exception, use the fallback method
                            } catch ( e ) {}
                        }

                        if ( elem ) {
                            this.empty().append( value );
                        }
                    }, null, value, arguments.length );
                },

                replaceWith: function() {
                    var ignored = [];

                    // Make the changes, replacing each non-ignored context element with the new content
                    return domManip( this, arguments, function( elem ) {
                        var parent = this.parentNode;

                        if ( jQuery.inArray( this, ignored ) < 0 ) {
                            jQuery.cleanData( getAll( this ) );
                            if ( parent ) {
                                parent.replaceChild( elem, this );
                            }
                        }

                        // Force callback invocation
                    }, ignored );
                }
            } );

            jQuery.each( {
                appendTo: "append",
                prependTo: "prepend",
                insertBefore: "before",
                insertAfter: "after",
                replaceAll: "replaceWith"
            }, function( name, original ) {
                jQuery.fn[ name ] = function( selector ) {
                    var elems,
                        ret = [],
                        insert = jQuery( selector ),
                        last = insert.length - 1,
                        i = 0;

                    for ( ; i <= last; i++ ) {
                        elems = i === last ? this : this.clone( true );
                        jQuery( insert[ i ] )[ original ]( elems );

                        // Support: Android <=4.0 only, PhantomJS 1 only
                        // .get() because push.apply(_, arraylike) throws on ancient WebKit
                        push.apply( ret, elems.get() );
                    }

                    return this.pushStack( ret );
                };
            } );
            var rnumnonpx = new RegExp( "^(" + pnum + ")(?!px)[a-z%]+$", "i" );

            var getStyles = function( elem ) {

                // Support: IE <=11 only, Firefox <=30 (#15098, #14150)
                // IE throws on elements created in popups
                // FF meanwhile throws on frame elements through "defaultView.getComputedStyle"
                var view = elem.ownerDocument.defaultView;

                if ( !view || !view.opener ) {
                    view = window;
                }

                return view.getComputedStyle( elem );
            };

            var swap = function( elem, options, callback ) {
                var ret, name,
                    old = {};

                // Remember the old values, and insert the new ones
                for ( name in options ) {
                    old[ name ] = elem.style[ name ];
                    elem.style[ name ] = options[ name ];
                }

                ret = callback.call( elem );

                // Revert the old values
                for ( name in options ) {
                    elem.style[ name ] = old[ name ];
                }

                return ret;
            };


            var rboxStyle = new RegExp( cssExpand.join( "|" ), "i" );



            ( function() {

                // Executing both pixelPosition & boxSizingReliable tests require only one layout
                // so they're executed at the same time to save the second computation.
                function computeStyleTests() {

                    // This is a singleton, we need to execute it only once
                    if ( !div ) {
                        return;
                    }

                    container.style.cssText = "position:absolute;left:-11111px;width:60px;" +
                        "margin-top:1px;padding:0;border:0";
                    div.style.cssText =
                        "position:relative;display:block;box-sizing:border-box;overflow:scroll;" +
                        "margin:auto;border:1px;padding:1px;" +
                        "width:60%;top:1%";
                    documentElement.appendChild( container ).appendChild( div );

                    var divStyle = window.getComputedStyle( div );
                    pixelPositionVal = divStyle.top !== "1%";

                    // Support: Android 4.0 - 4.3 only, Firefox <=3 - 44
                    reliableMarginLeftVal = roundPixelMeasures( divStyle.marginLeft ) === 12;

                    // Support: Android 4.0 - 4.3 only, Safari <=9.1 - 10.1, iOS <=7.0 - 9.3
                    // Some styles come back with percentage values, even though they shouldn't
                    div.style.right = "60%";
                    pixelBoxStylesVal = roundPixelMeasures( divStyle.right ) === 36;

                    // Support: IE 9 - 11 only
                    // Detect misreporting of content dimensions for box-sizing:border-box elements
                    boxSizingReliableVal = roundPixelMeasures( divStyle.width ) === 36;

                    // Support: IE 9 only
                    // Detect overflow:scroll screwiness (gh-3699)
                    // Support: Chrome <=64
                    // Don't get tricked when zoom affects offsetWidth (gh-4029)
                    div.style.position = "absolute";
                    scrollboxSizeVal = roundPixelMeasures( div.offsetWidth / 3 ) === 12;

                    documentElement.removeChild( container );

                    // Nullify the div so it wouldn't be stored in the memory and
                    // it will also be a sign that checks already performed
                    div = null;
                }

                function roundPixelMeasures( measure ) {
                    return Math.round( parseFloat( measure ) );
                }

                var pixelPositionVal, boxSizingReliableVal, scrollboxSizeVal, pixelBoxStylesVal,
                    reliableTrDimensionsVal, reliableMarginLeftVal,
                    container = document.createElement( "div" ),
                    div = document.createElement( "div" );

                // Finish early in limited (non-browser) environments
                if ( !div.style ) {
                    return;
                }

                // Support: IE <=9 - 11 only
                // Style of cloned element affects source element cloned (#8908)
                div.style.backgroundClip = "content-box";
                div.cloneNode( true ).style.backgroundClip = "";
                support.clearCloneStyle = div.style.backgroundClip === "content-box";

                jQuery.extend( support, {
                    boxSizingReliable: function() {
                        computeStyleTests();
                        return boxSizingReliableVal;
                    },
                    pixelBoxStyles: function() {
                        computeStyleTests();
                        return pixelBoxStylesVal;
                    },
                    pixelPosition: function() {
                        computeStyleTests();
                        return pixelPositionVal;
                    },
                    reliableMarginLeft: function() {
                        computeStyleTests();
                        return reliableMarginLeftVal;
                    },
                    scrollboxSize: function() {
                        computeStyleTests();
                        return scrollboxSizeVal;
                    },

                    // Support: IE 9 - 11+, Edge 15 - 18+
                    // IE/Edge misreport `getComputedStyle` of table rows with width/height
                    // set in CSS while `offset*` properties report correct values.
                    // Behavior in IE 9 is more subtle than in newer versions & it passes
                    // some versions of this test; make sure not to make it pass there!
                    reliableTrDimensions: function() {
                        var table, tr, trChild, trStyle;
                        if ( reliableTrDimensionsVal == null ) {
                            table = document.createElement( "table" );
                            tr = document.createElement( "tr" );
                            trChild = document.createElement( "div" );

                            table.style.cssText = "position:absolute;left:-11111px";
                            tr.style.height = "1px";
                            trChild.style.height = "9px";

                            documentElement
                                .appendChild( table )
                                .appendChild( tr )
                                .appendChild( trChild );

                            trStyle = window.getComputedStyle( tr );
                            reliableTrDimensionsVal = parseInt( trStyle.height ) > 3;

                            documentElement.removeChild( table );
                        }
                        return reliableTrDimensionsVal;
                    }
                } );
            } )();


            function curCSS( elem, name, computed ) {
                var width, minWidth, maxWidth, ret,

                    // Support: Firefox 51+
                    // Retrieving style before computed somehow
                    // fixes an issue with getting wrong values
                    // on detached elements
                    style = elem.style;

                computed = computed || getStyles( elem );

                // getPropertyValue is needed for:
                //   .css('filter') (IE 9 only, #12537)
                //   .css('--customProperty) (#3144)
                if ( computed ) {
                    ret = computed.getPropertyValue( name ) || computed[ name ];

                    if ( ret === "" && !isAttached( elem ) ) {
                        ret = jQuery.style( elem, name );
                    }

                    // A tribute to the "awesome hack by Dean Edwards"
                    // Android Browser returns percentage for some values,
                    // but width seems to be reliably pixels.
                    // This is against the CSSOM draft spec:
                    // https://drafts.csswg.org/cssom/#resolved-values
                    if ( !support.pixelBoxStyles() && rnumnonpx.test( ret ) && rboxStyle.test( name ) ) {

                        // Remember the original values
                        width = style.width;
                        minWidth = style.minWidth;
                        maxWidth = style.maxWidth;

                        // Put in the new values to get a computed value out
                        style.minWidth = style.maxWidth = style.width = ret;
                        ret = computed.width;

                        // Revert the changed values
                        style.width = width;
                        style.minWidth = minWidth;
                        style.maxWidth = maxWidth;
                    }
                }

                return ret !== undefined ?

                    // Support: IE <=9 - 11 only
                    // IE returns zIndex value as an integer.
                    ret + "" :
                    ret;
            }


            function addGetHookIf( conditionFn, hookFn ) {

                // Define the hook, we'll check on the first run if it's really needed.
                return {
                    get: function() {
                        if ( conditionFn() ) {

                            // Hook not needed (or it's not possible to use it due
                            // to missing dependency), remove it.
                            delete this.get;
                            return;
                        }

                        // Hook needed; redefine it so that the support test is not executed again.
                        return ( this.get = hookFn ).apply( this, arguments );
                    }
                };
            }


            var cssPrefixes = [ "Webkit", "Moz", "ms" ],
                emptyStyle = document.createElement( "div" ).style,
                vendorProps = {};

// Return a vendor-prefixed property or undefined
            function vendorPropName( name ) {

                // Check for vendor prefixed names
                var capName = name[ 0 ].toUpperCase() + name.slice( 1 ),
                    i = cssPrefixes.length;

                while ( i-- ) {
                    name = cssPrefixes[ i ] + capName;
                    if ( name in emptyStyle ) {
                        return name;
                    }
                }
            }

// Return a potentially-mapped jQuery.cssProps or vendor prefixed property
            function finalPropName( name ) {
                var final = jQuery.cssProps[ name ] || vendorProps[ name ];

                if ( final ) {
                    return final;
                }
                if ( name in emptyStyle ) {
                    return name;
                }
                return vendorProps[ name ] = vendorPropName( name ) || name;
            }


            var

                // Swappable if display is none or starts with table
                // except "table", "table-cell", or "table-caption"
                // See here for display values: https://developer.mozilla.org/en-US/docs/CSS/display
                rdisplayswap = /^(none|table(?!-c[ea]).+)/,
                rcustomProp = /^--/,
                cssShow = { position: "absolute", visibility: "hidden", display: "block" },
                cssNormalTransform = {
                    letterSpacing: "0",
                    fontWeight: "400"
                };

            function setPositiveNumber( _elem, value, subtract ) {

                // Any relative (+/-) values have already been
                // normalized at this point
                var matches = rcssNum.exec( value );
                return matches ?

                    // Guard against undefined "subtract", e.g., when used as in cssHooks
                    Math.max( 0, matches[ 2 ] - ( subtract || 0 ) ) + ( matches[ 3 ] || "px" ) :
                    value;
            }

            function boxModelAdjustment( elem, dimension, box, isBorderBox, styles, computedVal ) {
                var i = dimension === "width" ? 1 : 0,
                    extra = 0,
                    delta = 0;

                // Adjustment may not be necessary
                if ( box === ( isBorderBox ? "border" : "content" ) ) {
                    return 0;
                }

                for ( ; i < 4; i += 2 ) {

                    // Both box models exclude margin
                    if ( box === "margin" ) {
                        delta += jQuery.css( elem, box + cssExpand[ i ], true, styles );
                    }

                    // If we get here with a content-box, we're seeking "padding" or "border" or "margin"
                    if ( !isBorderBox ) {

                        // Add padding
                        delta += jQuery.css( elem, "padding" + cssExpand[ i ], true, styles );

                        // For "border" or "margin", add border
                        if ( box !== "padding" ) {
                            delta += jQuery.css( elem, "border" + cssExpand[ i ] + "Width", true, styles );

                            // But still keep track of it otherwise
                        } else {
                            extra += jQuery.css( elem, "border" + cssExpand[ i ] + "Width", true, styles );
                        }

                        // If we get here with a border-box (content + padding + border), we're seeking "content" or
                        // "padding" or "margin"
                    } else {

                        // For "content", subtract padding
                        if ( box === "content" ) {
                            delta -= jQuery.css( elem, "padding" + cssExpand[ i ], true, styles );
                        }

                        // For "content" or "padding", subtract border
                        if ( box !== "margin" ) {
                            delta -= jQuery.css( elem, "border" + cssExpand[ i ] + "Width", true, styles );
                        }
                    }
                }

                // Account for positive content-box scroll gutter when requested by providing computedVal
                if ( !isBorderBox && computedVal >= 0 ) {

                    // offsetWidth/offsetHeight is a rounded sum of content, padding, scroll gutter, and border
                    // Assuming integer scroll gutter, subtract the rest and round down
                    delta += Math.max( 0, Math.ceil(
                        elem[ "offset" + dimension[ 0 ].toUpperCase() + dimension.slice( 1 ) ] -
                        computedVal -
                        delta -
                        extra -
                        0.5

                        // If offsetWidth/offsetHeight is unknown, then we can't determine content-box scroll gutter
                        // Use an explicit zero to avoid NaN (gh-3964)
                    ) ) || 0;
                }

                return delta;
            }

            function getWidthOrHeight( elem, dimension, extra ) {

                // Start with computed style
                var styles = getStyles( elem ),

                    // To avoid forcing a reflow, only fetch boxSizing if we need it (gh-4322).
                    // Fake content-box until we know it's needed to know the true value.
                    boxSizingNeeded = !support.boxSizingReliable() || extra,
                    isBorderBox = boxSizingNeeded &&
                        jQuery.css( elem, "boxSizing", false, styles ) === "border-box",
                    valueIsBorderBox = isBorderBox,

                    val = curCSS( elem, dimension, styles ),
                    offsetProp = "offset" + dimension[ 0 ].toUpperCase() + dimension.slice( 1 );

                // Support: Firefox <=54
                // Return a confounding non-pixel value or feign ignorance, as appropriate.
                if ( rnumnonpx.test( val ) ) {
                    if ( !extra ) {
                        return val;
                    }
                    val = "auto";
                }


                // Support: IE 9 - 11 only
                // Use offsetWidth/offsetHeight for when box sizing is unreliable.
                // In those cases, the computed value can be trusted to be border-box.
                if ( ( !support.boxSizingReliable() && isBorderBox ||

                    // Support: IE 10 - 11+, Edge 15 - 18+
                    // IE/Edge misreport `getComputedStyle` of table rows with width/height
                    // set in CSS while `offset*` properties report correct values.
                    // Interestingly, in some cases IE 9 doesn't suffer from this issue.
                    !support.reliableTrDimensions() && nodeName( elem, "tr" ) ||

                    // Fall back to offsetWidth/offsetHeight when value is "auto"
                    // This happens for inline elements with no explicit setting (gh-3571)
                    val === "auto" ||

                    // Support: Android <=4.1 - 4.3 only
                    // Also use offsetWidth/offsetHeight for misreported inline dimensions (gh-3602)
                    !parseFloat( val ) && jQuery.css( elem, "display", false, styles ) === "inline" ) &&

                    // Make sure the element is visible & connected
                    elem.getClientRects().length ) {

                    isBorderBox = jQuery.css( elem, "boxSizing", false, styles ) === "border-box";

                    // Where available, offsetWidth/offsetHeight approximate border box dimensions.
                    // Where not available (e.g., SVG), assume unreliable box-sizing and interpret the
                    // retrieved value as a content box dimension.
                    valueIsBorderBox = offsetProp in elem;
                    if ( valueIsBorderBox ) {
                        val = elem[ offsetProp ];
                    }
                }

                // Normalize "" and auto
                val = parseFloat( val ) || 0;

                // Adjust for the element's box model
                return ( val +
                    boxModelAdjustment(
                        elem,
                        dimension,
                        extra || ( isBorderBox ? "border" : "content" ),
                        valueIsBorderBox,
                        styles,

                        // Provide the current computed size to request scroll gutter calculation (gh-3589)
                        val
                    )
                ) + "px";
            }

            jQuery.extend( {

                // Add in style property hooks for overriding the default
                // behavior of getting and setting a style property
                cssHooks: {
                    opacity: {
                        get: function( elem, computed ) {
                            if ( computed ) {

                                // We should always get a number back from opacity
                                var ret = curCSS( elem, "opacity" );
                                return ret === "" ? "1" : ret;
                            }
                        }
                    }
                },

                // Don't automatically add "px" to these possibly-unitless properties
                cssNumber: {
                    "animationIterationCount": true,
                    "columnCount": true,
                    "fillOpacity": true,
                    "flexGrow": true,
                    "flexShrink": true,
                    "fontWeight": true,
                    "gridArea": true,
                    "gridColumn": true,
                    "gridColumnEnd": true,
                    "gridColumnStart": true,
                    "gridRow": true,
                    "gridRowEnd": true,
                    "gridRowStart": true,
                    "lineHeight": true,
                    "opacity": true,
                    "order": true,
                    "orphans": true,
                    "widows": true,
                    "zIndex": true,
                    "zoom": true
                },

                // Add in properties whose names you wish to fix before
                // setting or getting the value
                cssProps: {},

                // Get and set the style property on a DOM Node
                style: function( elem, name, value, extra ) {

                    // Don't set styles on text and comment nodes
                    if ( !elem || elem.nodeType === 3 || elem.nodeType === 8 || !elem.style ) {
                        return;
                    }

                    // Make sure that we're working with the right name
                    var ret, type, hooks,
                        origName = camelCase( name ),
                        isCustomProp = rcustomProp.test( name ),
                        style = elem.style;

                    // Make sure that we're working with the right name. We don't
                    // want to query the value if it is a CSS custom property
                    // since they are user-defined.
                    if ( !isCustomProp ) {
                        name = finalPropName( origName );
                    }

                    // Gets hook for the prefixed version, then unprefixed version
                    hooks = jQuery.cssHooks[ name ] || jQuery.cssHooks[ origName ];

                    // Check if we're setting a value
                    if ( value !== undefined ) {
                        type = typeof value;

                        // Convert "+=" or "-=" to relative numbers (#7345)
                        if ( type === "string" && ( ret = rcssNum.exec( value ) ) && ret[ 1 ] ) {
                            value = adjustCSS( elem, name, ret );

                            // Fixes bug #9237
                            type = "number";
                        }

                        // Make sure that null and NaN values aren't set (#7116)
                        if ( value == null || value !== value ) {
                            return;
                        }

                        // If a number was passed in, add the unit (except for certain CSS properties)
                        // The isCustomProp check can be removed in jQuery 4.0 when we only auto-append
                        // "px" to a few hardcoded values.
                        if ( type === "number" && !isCustomProp ) {
                            value += ret && ret[ 3 ] || ( jQuery.cssNumber[ origName ] ? "" : "px" );
                        }

                        // background-* props affect original clone's values
                        if ( !support.clearCloneStyle && value === "" && name.indexOf( "background" ) === 0 ) {
                            style[ name ] = "inherit";
                        }

                        // If a hook was provided, use that value, otherwise just set the specified value
                        if ( !hooks || !( "set" in hooks ) ||
                            ( value = hooks.set( elem, value, extra ) ) !== undefined ) {

                            if ( isCustomProp ) {
                                style.setProperty( name, value );
                            } else {
                                style[ name ] = value;
                            }
                        }

                    } else {

                        // If a hook was provided get the non-computed value from there
                        if ( hooks && "get" in hooks &&
                            ( ret = hooks.get( elem, false, extra ) ) !== undefined ) {

                            return ret;
                        }

                        // Otherwise just get the value from the style object
                        return style[ name ];
                    }
                },

                css: function( elem, name, extra, styles ) {
                    var val, num, hooks,
                        origName = camelCase( name ),
                        isCustomProp = rcustomProp.test( name );

                    // Make sure that we're working with the right name. We don't
                    // want to modify the value if it is a CSS custom property
                    // since they are user-defined.
                    if ( !isCustomProp ) {
                        name = finalPropName( origName );
                    }

                    // Try prefixed name followed by the unprefixed name
                    hooks = jQuery.cssHooks[ name ] || jQuery.cssHooks[ origName ];

                    // If a hook was provided get the computed value from there
                    if ( hooks && "get" in hooks ) {
                        val = hooks.get( elem, true, extra );
                    }

                    // Otherwise, if a way to get the computed value exists, use that
                    if ( val === undefined ) {
                        val = curCSS( elem, name, styles );
                    }

                    // Convert "normal" to computed value
                    if ( val === "normal" && name in cssNormalTransform ) {
                        val = cssNormalTransform[ name ];
                    }

                    // Make numeric if forced or a qualifier was provided and val looks numeric
                    if ( extra === "" || extra ) {
                        num = parseFloat( val );
                        return extra === true || isFinite( num ) ? num || 0 : val;
                    }

                    return val;
                }
            } );

            jQuery.each( [ "height", "width" ], function( _i, dimension ) {
                jQuery.cssHooks[ dimension ] = {
                    get: function( elem, computed, extra ) {
                        if ( computed ) {

                            // Certain elements can have dimension info if we invisibly show them
                            // but it must have a current display style that would benefit
                            return rdisplayswap.test( jQuery.css( elem, "display" ) ) &&

                            // Support: Safari 8+
                            // Table columns in Safari have non-zero offsetWidth & zero
                            // getBoundingClientRect().width unless display is changed.
                            // Support: IE <=11 only
                            // Running getBoundingClientRect on a disconnected node
                            // in IE throws an error.
                            ( !elem.getClientRects().length || !elem.getBoundingClientRect().width ) ?
                                swap( elem, cssShow, function() {
                                    return getWidthOrHeight( elem, dimension, extra );
                                } ) :
                                getWidthOrHeight( elem, dimension, extra );
                        }
                    },

                    set: function( elem, value, extra ) {
                        var matches,
                            styles = getStyles( elem ),

                            // Only read styles.position if the test has a chance to fail
                            // to avoid forcing a reflow.
                            scrollboxSizeBuggy = !support.scrollboxSize() &&
                                styles.position === "absolute",

                            // To avoid forcing a reflow, only fetch boxSizing if we need it (gh-3991)
                            boxSizingNeeded = scrollboxSizeBuggy || extra,
                            isBorderBox = boxSizingNeeded &&
                                jQuery.css( elem, "boxSizing", false, styles ) === "border-box",
                            subtract = extra ?
                                boxModelAdjustment(
                                    elem,
                                    dimension,
                                    extra,
                                    isBorderBox,
                                    styles
                                ) :
                                0;

                        // Account for unreliable border-box dimensions by comparing offset* to computed and
                        // faking a content-box to get border and padding (gh-3699)
                        if ( isBorderBox && scrollboxSizeBuggy ) {
                            subtract -= Math.ceil(
                                elem[ "offset" + dimension[ 0 ].toUpperCase() + dimension.slice( 1 ) ] -
                                parseFloat( styles[ dimension ] ) -
                                boxModelAdjustment( elem, dimension, "border", false, styles ) -
                                0.5
                            );
                        }

                        // Convert to pixels if value adjustment is needed
                        if ( subtract && ( matches = rcssNum.exec( value ) ) &&
                            ( matches[ 3 ] || "px" ) !== "px" ) {

                            elem.style[ dimension ] = value;
                            value = jQuery.css( elem, dimension );
                        }

                        return setPositiveNumber( elem, value, subtract );
                    }
                };
            } );

            jQuery.cssHooks.marginLeft = addGetHookIf( support.reliableMarginLeft,
                function( elem, computed ) {
                    if ( computed ) {
                        return ( parseFloat( curCSS( elem, "marginLeft" ) ) ||
                            elem.getBoundingClientRect().left -
                            swap( elem, { marginLeft: 0 }, function() {
                                return elem.getBoundingClientRect().left;
                            } )
                        ) + "px";
                    }
                }
            );

// These hooks are used by animate to expand properties
            jQuery.each( {
                margin: "",
                padding: "",
                border: "Width"
            }, function( prefix, suffix ) {
                jQuery.cssHooks[ prefix + suffix ] = {
                    expand: function( value ) {
                        var i = 0,
                            expanded = {},

                            // Assumes a single number if not a string
                            parts = typeof value === "string" ? value.split( " " ) : [ value ];

                        for ( ; i < 4; i++ ) {
                            expanded[ prefix + cssExpand[ i ] + suffix ] =
                                parts[ i ] || parts[ i - 2 ] || parts[ 0 ];
                        }

                        return expanded;
                    }
                };

                if ( prefix !== "margin" ) {
                    jQuery.cssHooks[ prefix + suffix ].set = setPositiveNumber;
                }
            } );

            jQuery.fn.extend( {
                css: function( name, value ) {
                    return access( this, function( elem, name, value ) {
                        var styles, len,
                            map = {},
                            i = 0;

                        if ( Array.isArray( name ) ) {
                            styles = getStyles( elem );
                            len = name.length;

                            for ( ; i < len; i++ ) {
                                map[ name[ i ] ] = jQuery.css( elem, name[ i ], false, styles );
                            }

                            return map;
                        }

                        return value !== undefined ?
                            jQuery.style( elem, name, value ) :
                            jQuery.css( elem, name );
                    }, name, value, arguments.length > 1 );
                }
            } );


            function Tween( elem, options, prop, end, easing ) {
                return new Tween.prototype.init( elem, options, prop, end, easing );
            }
            jQuery.Tween = Tween;

            Tween.prototype = {
                constructor: Tween,
                init: function( elem, options, prop, end, easing, unit ) {
                    this.elem = elem;
                    this.prop = prop;
                    this.easing = easing || jQuery.easing._default;
                    this.options = options;
                    this.start = this.now = this.cur();
                    this.end = end;
                    this.unit = unit || ( jQuery.cssNumber[ prop ] ? "" : "px" );
                },
                cur: function() {
                    var hooks = Tween.propHooks[ this.prop ];

                    return hooks && hooks.get ?
                        hooks.get( this ) :
                        Tween.propHooks._default.get( this );
                },
                run: function( percent ) {
                    var eased,
                        hooks = Tween.propHooks[ this.prop ];

                    if ( this.options.duration ) {
                        this.pos = eased = jQuery.easing[ this.easing ](
                            percent, this.options.duration * percent, 0, 1, this.options.duration
                        );
                    } else {
                        this.pos = eased = percent;
                    }
                    this.now = ( this.end - this.start ) * eased + this.start;

                    if ( this.options.step ) {
                        this.options.step.call( this.elem, this.now, this );
                    }

                    if ( hooks && hooks.set ) {
                        hooks.set( this );
                    } else {
                        Tween.propHooks._default.set( this );
                    }
                    return this;
                }
            };

            Tween.prototype.init.prototype = Tween.prototype;

            Tween.propHooks = {
                _default: {
                    get: function( tween ) {
                        var result;

                        // Use a property on the element directly when it is not a DOM element,
                        // or when there is no matching style property that exists.
                        if ( tween.elem.nodeType !== 1 ||
                            tween.elem[ tween.prop ] != null && tween.elem.style[ tween.prop ] == null ) {
                            return tween.elem[ tween.prop ];
                        }

                        // Passing an empty string as a 3rd parameter to .css will automatically
                        // attempt a parseFloat and fallback to a string if the parse fails.
                        // Simple values such as "10px" are parsed to Float;
                        // complex values such as "rotate(1rad)" are returned as-is.
                        result = jQuery.css( tween.elem, tween.prop, "" );

                        // Empty strings, null, undefined and "auto" are converted to 0.
                        return !result || result === "auto" ? 0 : result;
                    },
                    set: function( tween ) {

                        // Use step hook for back compat.
                        // Use cssHook if its there.
                        // Use .style if available and use plain properties where available.
                        if ( jQuery.fx.step[ tween.prop ] ) {
                            jQuery.fx.step[ tween.prop ]( tween );
                        } else if ( tween.elem.nodeType === 1 && (
                            jQuery.cssHooks[ tween.prop ] ||
                            tween.elem.style[ finalPropName( tween.prop ) ] != null ) ) {
                            jQuery.style( tween.elem, tween.prop, tween.now + tween.unit );
                        } else {
                            tween.elem[ tween.prop ] = tween.now;
                        }
                    }
                }
            };

// Support: IE <=9 only
// Panic based approach to setting things on disconnected nodes
            Tween.propHooks.scrollTop = Tween.propHooks.scrollLeft = {
                set: function( tween ) {
                    if ( tween.elem.nodeType && tween.elem.parentNode ) {
                        tween.elem[ tween.prop ] = tween.now;
                    }
                }
            };

            jQuery.easing = {
                linear: function( p ) {
                    return p;
                },
                swing: function( p ) {
                    return 0.5 - Math.cos( p * Math.PI ) / 2;
                },
                _default: "swing"
            };

            jQuery.fx = Tween.prototype.init;

// Back compat <1.8 extension point
            jQuery.fx.step = {};




            var
                fxNow, inProgress,
                rfxtypes = /^(?:toggle|show|hide)$/,
                rrun = /queueHooks$/;

            function schedule() {
                if ( inProgress ) {
                    if ( document.hidden === false && window.requestAnimationFrame ) {
                        window.requestAnimationFrame( schedule );
                    } else {
                        window.setTimeout( schedule, jQuery.fx.interval );
                    }

                    jQuery.fx.tick();
                }
            }

// Animations created synchronously will run synchronously
            function createFxNow() {
                window.setTimeout( function() {
                    fxNow = undefined;
                } );
                return ( fxNow = Date.now() );
            }

// Generate parameters to create a standard animation
            function genFx( type, includeWidth ) {
                var which,
                    i = 0,
                    attrs = { height: type };

                // If we include width, step value is 1 to do all cssExpand values,
                // otherwise step value is 2 to skip over Left and Right
                includeWidth = includeWidth ? 1 : 0;
                for ( ; i < 4; i += 2 - includeWidth ) {
                    which = cssExpand[ i ];
                    attrs[ "margin" + which ] = attrs[ "padding" + which ] = type;
                }

                if ( includeWidth ) {
                    attrs.opacity = attrs.width = type;
                }

                return attrs;
            }

            function createTween( value, prop, animation ) {
                var tween,
                    collection = ( Animation.tweeners[ prop ] || [] ).concat( Animation.tweeners[ "*" ] ),
                    index = 0,
                    length = collection.length;
                for ( ; index < length; index++ ) {
                    if ( ( tween = collection[ index ].call( animation, prop, value ) ) ) {

                        // We're done with this property
                        return tween;
                    }
                }
            }

            function defaultPrefilter( elem, props, opts ) {
                var prop, value, toggle, hooks, oldfire, propTween, restoreDisplay, display,
                    isBox = "width" in props || "height" in props,
                    anim = this,
                    orig = {},
                    style = elem.style,
                    hidden = elem.nodeType && isHiddenWithinTree( elem ),
                    dataShow = dataPriv.get( elem, "fxshow" );

                // Queue-skipping animations hijack the fx hooks
                if ( !opts.queue ) {
                    hooks = jQuery._queueHooks( elem, "fx" );
                    if ( hooks.unqueued == null ) {
                        hooks.unqueued = 0;
                        oldfire = hooks.empty.fire;
                        hooks.empty.fire = function() {
                            if ( !hooks.unqueued ) {
                                oldfire();
                            }
                        };
                    }
                    hooks.unqueued++;

                    anim.always( function() {

                        // Ensure the complete handler is called before this completes
                        anim.always( function() {
                            hooks.unqueued--;
                            if ( !jQuery.queue( elem, "fx" ).length ) {
                                hooks.empty.fire();
                            }
                        } );
                    } );
                }

                // Detect show/hide animations
                for ( prop in props ) {
                    value = props[ prop ];
                    if ( rfxtypes.test( value ) ) {
                        delete props[ prop ];
                        toggle = toggle || value === "toggle";
                        if ( value === ( hidden ? "hide" : "show" ) ) {

                            // Pretend to be hidden if this is a "show" and
                            // there is still data from a stopped show/hide
                            if ( value === "show" && dataShow && dataShow[ prop ] !== undefined ) {
                                hidden = true;

                                // Ignore all other no-op show/hide data
                            } else {
                                continue;
                            }
                        }
                        orig[ prop ] = dataShow && dataShow[ prop ] || jQuery.style( elem, prop );
                    }
                }

                // Bail out if this is a no-op like .hide().hide()
                propTween = !jQuery.isEmptyObject( props );
                if ( !propTween && jQuery.isEmptyObject( orig ) ) {
                    return;
                }

                // Restrict "overflow" and "display" styles during box animations
                if ( isBox && elem.nodeType === 1 ) {

                    // Support: IE <=9 - 11, Edge 12 - 15
                    // Record all 3 overflow attributes because IE does not infer the shorthand
                    // from identically-valued overflowX and overflowY and Edge just mirrors
                    // the overflowX value there.
                    opts.overflow = [ style.overflow, style.overflowX, style.overflowY ];

                    // Identify a display type, preferring old show/hide data over the CSS cascade
                    restoreDisplay = dataShow && dataShow.display;
                    if ( restoreDisplay == null ) {
                        restoreDisplay = dataPriv.get( elem, "display" );
                    }
                    display = jQuery.css( elem, "display" );
                    if ( display === "none" ) {
                        if ( restoreDisplay ) {
                            display = restoreDisplay;
                        } else {

                            // Get nonempty value(s) by temporarily forcing visibility
                            showHide( [ elem ], true );
                            restoreDisplay = elem.style.display || restoreDisplay;
                            display = jQuery.css( elem, "display" );
                            showHide( [ elem ] );
                        }
                    }

                    // Animate inline elements as inline-block
                    if ( display === "inline" || display === "inline-block" && restoreDisplay != null ) {
                        if ( jQuery.css( elem, "float" ) === "none" ) {

                            // Restore the original display value at the end of pure show/hide animations
                            if ( !propTween ) {
                                anim.done( function() {
                                    style.display = restoreDisplay;
                                } );
                                if ( restoreDisplay == null ) {
                                    display = style.display;
                                    restoreDisplay = display === "none" ? "" : display;
                                }
                            }
                            style.display = "inline-block";
                        }
                    }
                }

                if ( opts.overflow ) {
                    style.overflow = "hidden";
                    anim.always( function() {
                        style.overflow = opts.overflow[ 0 ];
                        style.overflowX = opts.overflow[ 1 ];
                        style.overflowY = opts.overflow[ 2 ];
                    } );
                }

                // Implement show/hide animations
                propTween = false;
                for ( prop in orig ) {

                    // General show/hide setup for this element animation
                    if ( !propTween ) {
                        if ( dataShow ) {
                            if ( "hidden" in dataShow ) {
                                hidden = dataShow.hidden;
                            }
                        } else {
                            dataShow = dataPriv.access( elem, "fxshow", { display: restoreDisplay } );
                        }

                        // Store hidden/visible for toggle so `.stop().toggle()` "reverses"
                        if ( toggle ) {
                            dataShow.hidden = !hidden;
                        }

                        // Show elements before animating them
                        if ( hidden ) {
                            showHide( [ elem ], true );
                        }

                        /* eslint-disable no-loop-func */

                        anim.done( function() {

                            /* eslint-enable no-loop-func */

                            // The final step of a "hide" animation is actually hiding the element
                            if ( !hidden ) {
                                showHide( [ elem ] );
                            }
                            dataPriv.remove( elem, "fxshow" );
                            for ( prop in orig ) {
                                jQuery.style( elem, prop, orig[ prop ] );
                            }
                        } );
                    }

                    // Per-property setup
                    propTween = createTween( hidden ? dataShow[ prop ] : 0, prop, anim );
                    if ( !( prop in dataShow ) ) {
                        dataShow[ prop ] = propTween.start;
                        if ( hidden ) {
                            propTween.end = propTween.start;
                            propTween.start = 0;
                        }
                    }
                }
            }

            function propFilter( props, specialEasing ) {
                var index, name, easing, value, hooks;

                // camelCase, specialEasing and expand cssHook pass
                for ( index in props ) {
                    name = camelCase( index );
                    easing = specialEasing[ name ];
                    value = props[ index ];
                    if ( Array.isArray( value ) ) {
                        easing = value[ 1 ];
                        value = props[ index ] = value[ 0 ];
                    }

                    if ( index !== name ) {
                        props[ name ] = value;
                        delete props[ index ];
                    }

                    hooks = jQuery.cssHooks[ name ];
                    if ( hooks && "expand" in hooks ) {
                        value = hooks.expand( value );
                        delete props[ name ];

                        // Not quite $.extend, this won't overwrite existing keys.
                        // Reusing 'index' because we have the correct "name"
                        for ( index in value ) {
                            if ( !( index in props ) ) {
                                props[ index ] = value[ index ];
                                specialEasing[ index ] = easing;
                            }
                        }
                    } else {
                        specialEasing[ name ] = easing;
                    }
                }
            }

            function Animation( elem, properties, options ) {
                var result,
                    stopped,
                    index = 0,
                    length = Animation.prefilters.length,
                    deferred = jQuery.Deferred().always( function() {

                        // Don't match elem in the :animated selector
                        delete tick.elem;
                    } ),
                    tick = function() {
                        if ( stopped ) {
                            return false;
                        }
                        var currentTime = fxNow || createFxNow(),
                            remaining = Math.max( 0, animation.startTime + animation.duration - currentTime ),

                            // Support: Android 2.3 only
                            // Archaic crash bug won't allow us to use `1 - ( 0.5 || 0 )` (#12497)
                            temp = remaining / animation.duration || 0,
                            percent = 1 - temp,
                            index = 0,
                            length = animation.tweens.length;

                        for ( ; index < length; index++ ) {
                            animation.tweens[ index ].run( percent );
                        }

                        deferred.notifyWith( elem, [ animation, percent, remaining ] );

                        // If there's more to do, yield
                        if ( percent < 1 && length ) {
                            return remaining;
                        }

                        // If this was an empty animation, synthesize a final progress notification
                        if ( !length ) {
                            deferred.notifyWith( elem, [ animation, 1, 0 ] );
                        }

                        // Resolve the animation and report its conclusion
                        deferred.resolveWith( elem, [ animation ] );
                        return false;
                    },
                    animation = deferred.promise( {
                        elem: elem,
                        props: jQuery.extend( {}, properties ),
                        opts: jQuery.extend( true, {
                            specialEasing: {},
                            easing: jQuery.easing._default
                        }, options ),
                        originalProperties: properties,
                        originalOptions: options,
                        startTime: fxNow || createFxNow(),
                        duration: options.duration,
                        tweens: [],
                        createTween: function( prop, end ) {
                            var tween = jQuery.Tween( elem, animation.opts, prop, end,
                                animation.opts.specialEasing[ prop ] || animation.opts.easing );
                            animation.tweens.push( tween );
                            return tween;
                        },
                        stop: function( gotoEnd ) {
                            var index = 0,

                                // If we are going to the end, we want to run all the tweens
                                // otherwise we skip this part
                                length = gotoEnd ? animation.tweens.length : 0;
                            if ( stopped ) {
                                return this;
                            }
                            stopped = true;
                            for ( ; index < length; index++ ) {
                                animation.tweens[ index ].run( 1 );
                            }

                            // Resolve when we played the last frame; otherwise, reject
                            if ( gotoEnd ) {
                                deferred.notifyWith( elem, [ animation, 1, 0 ] );
                                deferred.resolveWith( elem, [ animation, gotoEnd ] );
                            } else {
                                deferred.rejectWith( elem, [ animation, gotoEnd ] );
                            }
                            return this;
                        }
                    } ),
                    props = animation.props;

                propFilter( props, animation.opts.specialEasing );

                for ( ; index < length; index++ ) {
                    result = Animation.prefilters[ index ].call( animation, elem, props, animation.opts );
                    if ( result ) {
                        if ( isFunction( result.stop ) ) {
                            jQuery._queueHooks( animation.elem, animation.opts.queue ).stop =
                                result.stop.bind( result );
                        }
                        return result;
                    }
                }

                jQuery.map( props, createTween, animation );

                if ( isFunction( animation.opts.start ) ) {
                    animation.opts.start.call( elem, animation );
                }

                // Attach callbacks from options
                animation
                    .progress( animation.opts.progress )
                    .done( animation.opts.done, animation.opts.complete )
                    .fail( animation.opts.fail )
                    .always( animation.opts.always );

                jQuery.fx.timer(
                    jQuery.extend( tick, {
                        elem: elem,
                        anim: animation,
                        queue: animation.opts.queue
                    } )
                );

                return animation;
            }

            jQuery.Animation = jQuery.extend( Animation, {

                tweeners: {
                    "*": [ function( prop, value ) {
                        var tween = this.createTween( prop, value );
                        adjustCSS( tween.elem, prop, rcssNum.exec( value ), tween );
                        return tween;
                    } ]
                },

                tweener: function( props, callback ) {
                    if ( isFunction( props ) ) {
                        callback = props;
                        props = [ "*" ];
                    } else {
                        props = props.match( rnothtmlwhite );
                    }

                    var prop,
                        index = 0,
                        length = props.length;

                    for ( ; index < length; index++ ) {
                        prop = props[ index ];
                        Animation.tweeners[ prop ] = Animation.tweeners[ prop ] || [];
                        Animation.tweeners[ prop ].unshift( callback );
                    }
                },

                prefilters: [ defaultPrefilter ],

                prefilter: function( callback, prepend ) {
                    if ( prepend ) {
                        Animation.prefilters.unshift( callback );
                    } else {
                        Animation.prefilters.push( callback );
                    }
                }
            } );

            jQuery.speed = function( speed, easing, fn ) {
                var opt = speed && typeof speed === "object" ? jQuery.extend( {}, speed ) : {
                    complete: fn || !fn && easing ||
                        isFunction( speed ) && speed,
                    duration: speed,
                    easing: fn && easing || easing && !isFunction( easing ) && easing
                };

                // Go to the end state if fx are off
                if ( jQuery.fx.off ) {
                    opt.duration = 0;

                } else {
                    if ( typeof opt.duration !== "number" ) {
                        if ( opt.duration in jQuery.fx.speeds ) {
                            opt.duration = jQuery.fx.speeds[ opt.duration ];

                        } else {
                            opt.duration = jQuery.fx.speeds._default;
                        }
                    }
                }

                // Normalize opt.queue - true/undefined/null -> "fx"
                if ( opt.queue == null || opt.queue === true ) {
                    opt.queue = "fx";
                }

                // Queueing
                opt.old = opt.complete;

                opt.complete = function() {
                    if ( isFunction( opt.old ) ) {
                        opt.old.call( this );
                    }

                    if ( opt.queue ) {
                        jQuery.dequeue( this, opt.queue );
                    }
                };

                return opt;
            };

            jQuery.fn.extend( {
                fadeTo: function( speed, to, easing, callback ) {

                    // Show any hidden elements after setting opacity to 0
                    return this.filter( isHiddenWithinTree ).css( "opacity", 0 ).show()

                    // Animate to the value specified
                        .end().animate( { opacity: to }, speed, easing, callback );
                },
                animate: function( prop, speed, easing, callback ) {
                    var empty = jQuery.isEmptyObject( prop ),
                        optall = jQuery.speed( speed, easing, callback ),
                        doAnimation = function() {

                            // Operate on a copy of prop so per-property easing won't be lost
                            var anim = Animation( this, jQuery.extend( {}, prop ), optall );

                            // Empty animations, or finishing resolves immediately
                            if ( empty || dataPriv.get( this, "finish" ) ) {
                                anim.stop( true );
                            }
                        };
                    doAnimation.finish = doAnimation;

                    return empty || optall.queue === false ?
                        this.each( doAnimation ) :
                        this.queue( optall.queue, doAnimation );
                },
                stop: function( type, clearQueue, gotoEnd ) {
                    var stopQueue = function( hooks ) {
                        var stop = hooks.stop;
                        delete hooks.stop;
                        stop( gotoEnd );
                    };

                    if ( typeof type !== "string" ) {
                        gotoEnd = clearQueue;
                        clearQueue = type;
                        type = undefined;
                    }
                    if ( clearQueue ) {
                        this.queue( type || "fx", [] );
                    }

                    return this.each( function() {
                        var dequeue = true,
                            index = type != null && type + "queueHooks",
                            timers = jQuery.timers,
                            data = dataPriv.get( this );

                        if ( index ) {
                            if ( data[ index ] && data[ index ].stop ) {
                                stopQueue( data[ index ] );
                            }
                        } else {
                            for ( index in data ) {
                                if ( data[ index ] && data[ index ].stop && rrun.test( index ) ) {
                                    stopQueue( data[ index ] );
                                }
                            }
                        }

                        for ( index = timers.length; index--; ) {
                            if ( timers[ index ].elem === this &&
                                ( type == null || timers[ index ].queue === type ) ) {

                                timers[ index ].anim.stop( gotoEnd );
                                dequeue = false;
                                timers.splice( index, 1 );
                            }
                        }

                        // Start the next in the queue if the last step wasn't forced.
                        // Timers currently will call their complete callbacks, which
                        // will dequeue but only if they were gotoEnd.
                        if ( dequeue || !gotoEnd ) {
                            jQuery.dequeue( this, type );
                        }
                    } );
                },
                finish: function( type ) {
                    if ( type !== false ) {
                        type = type || "fx";
                    }
                    return this.each( function() {
                        var index,
                            data = dataPriv.get( this ),
                            queue = data[ type + "queue" ],
                            hooks = data[ type + "queueHooks" ],
                            timers = jQuery.timers,
                            length = queue ? queue.length : 0;

                        // Enable finishing flag on private data
                        data.finish = true;

                        // Empty the queue first
                        jQuery.queue( this, type, [] );

                        if ( hooks && hooks.stop ) {
                            hooks.stop.call( this, true );
                        }

                        // Look for any active animations, and finish them
                        for ( index = timers.length; index--; ) {
                            if ( timers[ index ].elem === this && timers[ index ].queue === type ) {
                                timers[ index ].anim.stop( true );
                                timers.splice( index, 1 );
                            }
                        }

                        // Look for any animations in the old queue and finish them
                        for ( index = 0; index < length; index++ ) {
                            if ( queue[ index ] && queue[ index ].finish ) {
                                queue[ index ].finish.call( this );
                            }
                        }

                        // Turn off finishing flag
                        delete data.finish;
                    } );
                }
            } );

            jQuery.each( [ "toggle", "show", "hide" ], function( _i, name ) {
                var cssFn = jQuery.fn[ name ];
                jQuery.fn[ name ] = function( speed, easing, callback ) {
                    return speed == null || typeof speed === "boolean" ?
                        cssFn.apply( this, arguments ) :
                        this.animate( genFx( name, true ), speed, easing, callback );
                };
            } );

// Generate shortcuts for custom animations
            jQuery.each( {
                slideDown: genFx( "show" ),
                slideUp: genFx( "hide" ),
                slideToggle: genFx( "toggle" ),
                fadeIn: { opacity: "show" },
                fadeOut: { opacity: "hide" },
                fadeToggle: { opacity: "toggle" }
            }, function( name, props ) {
                jQuery.fn[ name ] = function( speed, easing, callback ) {
                    return this.animate( props, speed, easing, callback );
                };
            } );

            jQuery.timers = [];
            jQuery.fx.tick = function() {
                var timer,
                    i = 0,
                    timers = jQuery.timers;

                fxNow = Date.now();

                for ( ; i < timers.length; i++ ) {
                    timer = timers[ i ];

                    // Run the timer and safely remove it when done (allowing for external removal)
                    if ( !timer() && timers[ i ] === timer ) {
                        timers.splice( i--, 1 );
                    }
                }

                if ( !timers.length ) {
                    jQuery.fx.stop();
                }
                fxNow = undefined;
            };

            jQuery.fx.timer = function( timer ) {
                jQuery.timers.push( timer );
                jQuery.fx.start();
            };

            jQuery.fx.interval = 13;
            jQuery.fx.start = function() {
                if ( inProgress ) {
                    return;
                }

                inProgress = true;
                schedule();
            };

            jQuery.fx.stop = function() {
                inProgress = null;
            };

            jQuery.fx.speeds = {
                slow: 600,
                fast: 200,

                // Default speed
                _default: 400
            };


// Based off of the plugin by Clint Helfers, with permission.
// https://web.archive.org/web/20100324014747/http://blindsignals.com/index.php/2009/07/jquery-delay/
            jQuery.fn.delay = function( time, type ) {
                time = jQuery.fx ? jQuery.fx.speeds[ time ] || time : time;
                type = type || "fx";

                return this.queue( type, function( next, hooks ) {
                    var timeout = window.setTimeout( next, time );
                    hooks.stop = function() {
                        window.clearTimeout( timeout );
                    };
                } );
            };


            ( function() {
                var input = document.createElement( "input" ),
                    select = document.createElement( "select" ),
                    opt = select.appendChild( document.createElement( "option" ) );

                input.type = "checkbox";

                // Support: Android <=4.3 only
                // Default value for a checkbox should be "on"
                support.checkOn = input.value !== "";

                // Support: IE <=11 only
                // Must access selectedIndex to make default options select
                support.optSelected = opt.selected;

                // Support: IE <=11 only
                // An input loses its value after becoming a radio
                input = document.createElement( "input" );
                input.value = "t";
                input.type = "radio";
                support.radioValue = input.value === "t";
            } )();


            var boolHook,
                attrHandle = jQuery.expr.attrHandle;

            jQuery.fn.extend( {
                attr: function( name, value ) {
                    return access( this, jQuery.attr, name, value, arguments.length > 1 );
                },

                removeAttr: function( name ) {
                    return this.each( function() {
                        jQuery.removeAttr( this, name );
                    } );
                }
            } );

            jQuery.extend( {
                attr: function( elem, name, value ) {
                    var ret, hooks,
                        nType = elem.nodeType;

                    // Don't get/set attributes on text, comment and attribute nodes
                    if ( nType === 3 || nType === 8 || nType === 2 ) {
                        return;
                    }

                    // Fallback to prop when attributes are not supported
                    if ( typeof elem.getAttribute === "undefined" ) {
                        return jQuery.prop( elem, name, value );
                    }

                    // Attribute hooks are determined by the lowercase version
                    // Grab necessary hook if one is defined
                    if ( nType !== 1 || !jQuery.isXMLDoc( elem ) ) {
                        hooks = jQuery.attrHooks[ name.toLowerCase() ] ||
                            ( jQuery.expr.match.bool.test( name ) ? boolHook : undefined );
                    }

                    if ( value !== undefined ) {
                        if ( value === null ) {
                            jQuery.removeAttr( elem, name );
                            return;
                        }

                        if ( hooks && "set" in hooks &&
                            ( ret = hooks.set( elem, value, name ) ) !== undefined ) {
                            return ret;
                        }

                        elem.setAttribute( name, value + "" );
                        return value;
                    }

                    if ( hooks && "get" in hooks && ( ret = hooks.get( elem, name ) ) !== null ) {
                        return ret;
                    }

                    ret = jQuery.find.attr( elem, name );

                    // Non-existent attributes return null, we normalize to undefined
                    return ret == null ? undefined : ret;
                },

                attrHooks: {
                    type: {
                        set: function( elem, value ) {
                            if ( !support.radioValue && value === "radio" &&
                                nodeName( elem, "input" ) ) {
                                var val = elem.value;
                                elem.setAttribute( "type", value );
                                if ( val ) {
                                    elem.value = val;
                                }
                                return value;
                            }
                        }
                    }
                },

                removeAttr: function( elem, value ) {
                    var name,
                        i = 0,

                        // Attribute names can contain non-HTML whitespace characters
                        // https://html.spec.whatwg.org/multipage/syntax.html#attributes-2
                        attrNames = value && value.match( rnothtmlwhite );

                    if ( attrNames && elem.nodeType === 1 ) {
                        while ( ( name = attrNames[ i++ ] ) ) {
                            elem.removeAttribute( name );
                        }
                    }
                }
            } );

// Hooks for boolean attributes
            boolHook = {
                set: function( elem, value, name ) {
                    if ( value === false ) {

                        // Remove boolean attributes when set to false
                        jQuery.removeAttr( elem, name );
                    } else {
                        elem.setAttribute( name, name );
                    }
                    return name;
                }
            };

            jQuery.each( jQuery.expr.match.bool.source.match( /\w+/g ), function( _i, name ) {
                var getter = attrHandle[ name ] || jQuery.find.attr;

                attrHandle[ name ] = function( elem, name, isXML ) {
                    var ret, handle,
                        lowercaseName = name.toLowerCase();

                    if ( !isXML ) {

                        // Avoid an infinite loop by temporarily removing this function from the getter
                        handle = attrHandle[ lowercaseName ];
                        attrHandle[ lowercaseName ] = ret;
                        ret = getter( elem, name, isXML ) != null ?
                            lowercaseName :
                            null;
                        attrHandle[ lowercaseName ] = handle;
                    }
                    return ret;
                };
            } );




            var rfocusable = /^(?:input|select|textarea|button)$/i,
                rclickable = /^(?:a|area)$/i;

            jQuery.fn.extend( {
                prop: function( name, value ) {
                    return access( this, jQuery.prop, name, value, arguments.length > 1 );
                },

                removeProp: function( name ) {
                    return this.each( function() {
                        delete this[ jQuery.propFix[ name ] || name ];
                    } );
                }
            } );

            jQuery.extend( {
                prop: function( elem, name, value ) {
                    var ret, hooks,
                        nType = elem.nodeType;

                    // Don't get/set properties on text, comment and attribute nodes
                    if ( nType === 3 || nType === 8 || nType === 2 ) {
                        return;
                    }

                    if ( nType !== 1 || !jQuery.isXMLDoc( elem ) ) {

                        // Fix name and attach hooks
                        name = jQuery.propFix[ name ] || name;
                        hooks = jQuery.propHooks[ name ];
                    }

                    if ( value !== undefined ) {
                        if ( hooks && "set" in hooks &&
                            ( ret = hooks.set( elem, value, name ) ) !== undefined ) {
                            return ret;
                        }

                        return ( elem[ name ] = value );
                    }

                    if ( hooks && "get" in hooks && ( ret = hooks.get( elem, name ) ) !== null ) {
                        return ret;
                    }

                    return elem[ name ];
                },

                propHooks: {
                    tabIndex: {
                        get: function( elem ) {

                            // Support: IE <=9 - 11 only
                            // elem.tabIndex doesn't always return the
                            // correct value when it hasn't been explicitly set
                            // https://web.archive.org/web/20141116233347/http://fluidproject.org/blog/2008/01/09/getting-setting-and-removing-tabindex-values-with-javascript/
                            // Use proper attribute retrieval(#12072)
                            var tabindex = jQuery.find.attr( elem, "tabindex" );

                            if ( tabindex ) {
                                return parseInt( tabindex, 10 );
                            }

                            if (
                                rfocusable.test( elem.nodeName ) ||
                                rclickable.test( elem.nodeName ) &&
                                elem.href
                            ) {
                                return 0;
                            }

                            return -1;
                        }
                    }
                },

                propFix: {
                    "for": "htmlFor",
                    "class": "className"
                }
            } );

// Support: IE <=11 only
// Accessing the selectedIndex property
// forces the browser to respect setting selected
// on the option
// The getter ensures a default option is selected
// when in an optgroup
// eslint rule "no-unused-expressions" is disabled for this code
// since it considers such accessions noop
            if ( !support.optSelected ) {
                jQuery.propHooks.selected = {
                    get: function( elem ) {

                        /* eslint no-unused-expressions: "off" */

                        var parent = elem.parentNode;
                        if ( parent && parent.parentNode ) {
                            parent.parentNode.selectedIndex;
                        }
                        return null;
                    },
                    set: function( elem ) {

                        /* eslint no-unused-expressions: "off" */

                        var parent = elem.parentNode;
                        if ( parent ) {
                            parent.selectedIndex;

                            if ( parent.parentNode ) {
                                parent.parentNode.selectedIndex;
                            }
                        }
                    }
                };
            }

            jQuery.each( [
                "tabIndex",
                "readOnly",
                "maxLength",
                "cellSpacing",
                "cellPadding",
                "rowSpan",
                "colSpan",
                "useMap",
                "frameBorder",
                "contentEditable"
            ], function() {
                jQuery.propFix[ this.toLowerCase() ] = this;
            } );




            // Strip and collapse whitespace according to HTML spec
            // https://infra.spec.whatwg.org/#strip-and-collapse-ascii-whitespace
            function stripAndCollapse( value ) {
                var tokens = value.match( rnothtmlwhite ) || [];
                return tokens.join( " " );
            }


            function getClass( elem ) {
                return elem.getAttribute && elem.getAttribute( "class" ) || "";
            }

            function classesToArray( value ) {
                if ( Array.isArray( value ) ) {
                    return value;
                }
                if ( typeof value === "string" ) {
                    return value.match( rnothtmlwhite ) || [];
                }
                return [];
            }

            jQuery.fn.extend( {
                addClass: function( value ) {
                    var classes, elem, cur, curValue, clazz, j, finalValue,
                        i = 0;

                    if ( isFunction( value ) ) {
                        return this.each( function( j ) {
                            jQuery( this ).addClass( value.call( this, j, getClass( this ) ) );
                        } );
                    }

                    classes = classesToArray( value );

                    if ( classes.length ) {
                        while ( ( elem = this[ i++ ] ) ) {
                            curValue = getClass( elem );
                            cur = elem.nodeType === 1 && ( " " + stripAndCollapse( curValue ) + " " );

                            if ( cur ) {
                                j = 0;
                                while ( ( clazz = classes[ j++ ] ) ) {
                                    if ( cur.indexOf( " " + clazz + " " ) < 0 ) {
                                        cur += clazz + " ";
                                    }
                                }

                                // Only assign if different to avoid unneeded rendering.
                                finalValue = stripAndCollapse( cur );
                                if ( curValue !== finalValue ) {
                                    elem.setAttribute( "class", finalValue );
                                }
                            }
                        }
                    }

                    return this;
                },

                removeClass: function( value ) {
                    var classes, elem, cur, curValue, clazz, j, finalValue,
                        i = 0;

                    if ( isFunction( value ) ) {
                        return this.each( function( j ) {
                            jQuery( this ).removeClass( value.call( this, j, getClass( this ) ) );
                        } );
                    }

                    if ( !arguments.length ) {
                        return this.attr( "class", "" );
                    }

                    classes = classesToArray( value );

                    if ( classes.length ) {
                        while ( ( elem = this[ i++ ] ) ) {
                            curValue = getClass( elem );

                            // This expression is here for better compressibility (see addClass)
                            cur = elem.nodeType === 1 && ( " " + stripAndCollapse( curValue ) + " " );

                            if ( cur ) {
                                j = 0;
                                while ( ( clazz = classes[ j++ ] ) ) {

                                    // Remove *all* instances
                                    while ( cur.indexOf( " " + clazz + " " ) > -1 ) {
                                        cur = cur.replace( " " + clazz + " ", " " );
                                    }
                                }

                                // Only assign if different to avoid unneeded rendering.
                                finalValue = stripAndCollapse( cur );
                                if ( curValue !== finalValue ) {
                                    elem.setAttribute( "class", finalValue );
                                }
                            }
                        }
                    }

                    return this;
                },

                toggleClass: function( value, stateVal ) {
                    var type = typeof value,
                        isValidValue = type === "string" || Array.isArray( value );

                    if ( typeof stateVal === "boolean" && isValidValue ) {
                        return stateVal ? this.addClass( value ) : this.removeClass( value );
                    }

                    if ( isFunction( value ) ) {
                        return this.each( function( i ) {
                            jQuery( this ).toggleClass(
                                value.call( this, i, getClass( this ), stateVal ),
                                stateVal
                            );
                        } );
                    }

                    return this.each( function() {
                        var className, i, self, classNames;

                        if ( isValidValue ) {

                            // Toggle individual class names
                            i = 0;
                            self = jQuery( this );
                            classNames = classesToArray( value );

                            while ( ( className = classNames[ i++ ] ) ) {

                                // Check each className given, space separated list
                                if ( self.hasClass( className ) ) {
                                    self.removeClass( className );
                                } else {
                                    self.addClass( className );
                                }
                            }

                            // Toggle whole class name
                        } else if ( value === undefined || type === "boolean" ) {
                            className = getClass( this );
                            if ( className ) {

                                // Store className if set
                                dataPriv.set( this, "__className__", className );
                            }

                            // If the element has a class name or if we're passed `false`,
                            // then remove the whole classname (if there was one, the above saved it).
                            // Otherwise bring back whatever was previously saved (if anything),
                            // falling back to the empty string if nothing was stored.
                            if ( this.setAttribute ) {
                                this.setAttribute( "class",
                                    className || value === false ?
                                        "" :
                                        dataPriv.get( this, "__className__" ) || ""
                                );
                            }
                        }
                    } );
                },

                hasClass: function( selector ) {
                    var className, elem,
                        i = 0;

                    className = " " + selector + " ";
                    while ( ( elem = this[ i++ ] ) ) {
                        if ( elem.nodeType === 1 &&
                            ( " " + stripAndCollapse( getClass( elem ) ) + " " ).indexOf( className ) > -1 ) {
                            return true;
                        }
                    }

                    return false;
                }
            } );




            var rreturn = /\r/g;

            jQuery.fn.extend( {
                val: function( value ) {
                    var hooks, ret, valueIsFunction,
                        elem = this[ 0 ];

                    if ( !arguments.length ) {
                        if ( elem ) {
                            hooks = jQuery.valHooks[ elem.type ] ||
                                jQuery.valHooks[ elem.nodeName.toLowerCase() ];

                            if ( hooks &&
                                "get" in hooks &&
                                ( ret = hooks.get( elem, "value" ) ) !== undefined
                            ) {
                                return ret;
                            }

                            ret = elem.value;

                            // Handle most common string cases
                            if ( typeof ret === "string" ) {
                                return ret.replace( rreturn, "" );
                            }

                            // Handle cases where value is null/undef or number
                            return ret == null ? "" : ret;
                        }

                        return;
                    }

                    valueIsFunction = isFunction( value );

                    return this.each( function( i ) {
                        var val;

                        if ( this.nodeType !== 1 ) {
                            return;
                        }

                        if ( valueIsFunction ) {
                            val = value.call( this, i, jQuery( this ).val() );
                        } else {
                            val = value;
                        }

                        // Treat null/undefined as ""; convert numbers to string
                        if ( val == null ) {
                            val = "";

                        } else if ( typeof val === "number" ) {
                            val += "";

                        } else if ( Array.isArray( val ) ) {
                            val = jQuery.map( val, function( value ) {
                                return value == null ? "" : value + "";
                            } );
                        }

                        hooks = jQuery.valHooks[ this.type ] || jQuery.valHooks[ this.nodeName.toLowerCase() ];

                        // If set returns undefined, fall back to normal setting
                        if ( !hooks || !( "set" in hooks ) || hooks.set( this, val, "value" ) === undefined ) {
                            this.value = val;
                        }
                    } );
                }
            } );

            jQuery.extend( {
                valHooks: {
                    option: {
                        get: function( elem ) {

                            var val = jQuery.find.attr( elem, "value" );
                            return val != null ?
                                val :

                                // Support: IE <=10 - 11 only
                                // option.text throws exceptions (#14686, #14858)
                                // Strip and collapse whitespace
                                // https://html.spec.whatwg.org/#strip-and-collapse-whitespace
                                stripAndCollapse( jQuery.text( elem ) );
                        }
                    },
                    select: {
                        get: function( elem ) {
                            var value, option, i,
                                options = elem.options,
                                index = elem.selectedIndex,
                                one = elem.type === "select-one",
                                values = one ? null : [],
                                max = one ? index + 1 : options.length;

                            if ( index < 0 ) {
                                i = max;

                            } else {
                                i = one ? index : 0;
                            }

                            // Loop through all the selected options
                            for ( ; i < max; i++ ) {
                                option = options[ i ];

                                // Support: IE <=9 only
                                // IE8-9 doesn't update selected after form reset (#2551)
                                if ( ( option.selected || i === index ) &&

                                    // Don't return options that are disabled or in a disabled optgroup
                                    !option.disabled &&
                                    ( !option.parentNode.disabled ||
                                        !nodeName( option.parentNode, "optgroup" ) ) ) {

                                    // Get the specific value for the option
                                    value = jQuery( option ).val();

                                    // We don't need an array for one selects
                                    if ( one ) {
                                        return value;
                                    }

                                    // Multi-Selects return an array
                                    values.push( value );
                                }
                            }

                            return values;
                        },

                        set: function( elem, value ) {
                            var optionSet, option,
                                options = elem.options,
                                values = jQuery.makeArray( value ),
                                i = options.length;

                            while ( i-- ) {
                                option = options[ i ];

                                /* eslint-disable no-cond-assign */

                                if ( option.selected =
                                    jQuery.inArray( jQuery.valHooks.option.get( option ), values ) > -1
                                ) {
                                    optionSet = true;
                                }

                                /* eslint-enable no-cond-assign */
                            }

                            // Force browsers to behave consistently when non-matching value is set
                            if ( !optionSet ) {
                                elem.selectedIndex = -1;
                            }
                            return values;
                        }
                    }
                }
            } );

// Radios and checkboxes getter/setter
            jQuery.each( [ "radio", "checkbox" ], function() {
                jQuery.valHooks[ this ] = {
                    set: function( elem, value ) {
                        if ( Array.isArray( value ) ) {
                            return ( elem.checked = jQuery.inArray( jQuery( elem ).val(), value ) > -1 );
                        }
                    }
                };
                if ( !support.checkOn ) {
                    jQuery.valHooks[ this ].get = function( elem ) {
                        return elem.getAttribute( "value" ) === null ? "on" : elem.value;
                    };
                }
            } );




// Return jQuery for attributes-only inclusion


            support.focusin = "onfocusin" in window;


            var rfocusMorph = /^(?:focusinfocus|focusoutblur)$/,
                stopPropagationCallback = function( e ) {
                    e.stopPropagation();
                };

            jQuery.extend( jQuery.event, {

                trigger: function( event, data, elem, onlyHandlers ) {

                    var i, cur, tmp, bubbleType, ontype, handle, special, lastElement,
                        eventPath = [ elem || document ],
                        type = hasOwn.call( event, "type" ) ? event.type : event,
                        namespaces = hasOwn.call( event, "namespace" ) ? event.namespace.split( "." ) : [];

                    cur = lastElement = tmp = elem = elem || document;

                    // Don't do events on text and comment nodes
                    if ( elem.nodeType === 3 || elem.nodeType === 8 ) {
                        return;
                    }

                    // focus/blur morphs to focusin/out; ensure we're not firing them right now
                    if ( rfocusMorph.test( type + jQuery.event.triggered ) ) {
                        return;
                    }

                    if ( type.indexOf( "." ) > -1 ) {

                        // Namespaced trigger; create a regexp to match event type in handle()
                        namespaces = type.split( "." );
                        type = namespaces.shift();
                        namespaces.sort();
                    }
                    ontype = type.indexOf( ":" ) < 0 && "on" + type;

                    // Caller can pass in a jQuery.Event object, Object, or just an event type string
                    event = event[ jQuery.expando ] ?
                        event :
                        new jQuery.Event( type, typeof event === "object" && event );

                    // Trigger bitmask: & 1 for native handlers; & 2 for jQuery (always true)
                    event.isTrigger = onlyHandlers ? 2 : 3;
                    event.namespace = namespaces.join( "." );
                    event.rnamespace = event.namespace ?
                        new RegExp( "(^|\\.)" + namespaces.join( "\\.(?:.*\\.|)" ) + "(\\.|$)" ) :
                        null;

                    // Clean up the event in case it is being reused
                    event.result = undefined;
                    if ( !event.target ) {
                        event.target = elem;
                    }

                    // Clone any incoming data and prepend the event, creating the handler arg list
                    data = data == null ?
                        [ event ] :
                        jQuery.makeArray( data, [ event ] );

                    // Allow special events to draw outside the lines
                    special = jQuery.event.special[ type ] || {};
                    if ( !onlyHandlers && special.trigger && special.trigger.apply( elem, data ) === false ) {
                        return;
                    }

                    // Determine event propagation path in advance, per W3C events spec (#9951)
                    // Bubble up to document, then to window; watch for a global ownerDocument var (#9724)
                    if ( !onlyHandlers && !special.noBubble && !isWindow( elem ) ) {

                        bubbleType = special.delegateType || type;
                        if ( !rfocusMorph.test( bubbleType + type ) ) {
                            cur = cur.parentNode;
                        }
                        for ( ; cur; cur = cur.parentNode ) {
                            eventPath.push( cur );
                            tmp = cur;
                        }

                        // Only add window if we got to document (e.g., not plain obj or detached DOM)
                        if ( tmp === ( elem.ownerDocument || document ) ) {
                            eventPath.push( tmp.defaultView || tmp.parentWindow || window );
                        }
                    }

                    // Fire handlers on the event path
                    i = 0;
                    while ( ( cur = eventPath[ i++ ] ) && !event.isPropagationStopped() ) {
                        lastElement = cur;
                        event.type = i > 1 ?
                            bubbleType :
                            special.bindType || type;

                        // jQuery handler
                        handle = (
                                dataPriv.get( cur, "events" ) || Object.create( null )
                            )[ event.type ] &&
                            dataPriv.get( cur, "handle" );
                        if ( handle ) {
                            handle.apply( cur, data );
                        }

                        // Native handler
                        handle = ontype && cur[ ontype ];
                        if ( handle && handle.apply && acceptData( cur ) ) {
                            event.result = handle.apply( cur, data );
                            if ( event.result === false ) {
                                event.preventDefault();
                            }
                        }
                    }
                    event.type = type;

                    // If nobody prevented the default action, do it now
                    if ( !onlyHandlers && !event.isDefaultPrevented() ) {

                        if ( ( !special._default ||
                            special._default.apply( eventPath.pop(), data ) === false ) &&
                            acceptData( elem ) ) {

                            // Call a native DOM method on the target with the same name as the event.
                            // Don't do default actions on window, that's where global variables be (#6170)
                            if ( ontype && isFunction( elem[ type ] ) && !isWindow( elem ) ) {

                                // Don't re-trigger an onFOO event when we call its FOO() method
                                tmp = elem[ ontype ];

                                if ( tmp ) {
                                    elem[ ontype ] = null;
                                }

                                // Prevent re-triggering of the same event, since we already bubbled it above
                                jQuery.event.triggered = type;

                                if ( event.isPropagationStopped() ) {
                                    lastElement.addEventListener( type, stopPropagationCallback );
                                }

                                elem[ type ]();

                                if ( event.isPropagationStopped() ) {
                                    lastElement.removeEventListener( type, stopPropagationCallback );
                                }

                                jQuery.event.triggered = undefined;

                                if ( tmp ) {
                                    elem[ ontype ] = tmp;
                                }
                            }
                        }
                    }

                    return event.result;
                },

                // Piggyback on a donor event to simulate a different one
                // Used only for `focus(in | out)` events
                simulate: function( type, elem, event ) {
                    var e = jQuery.extend(
                        new jQuery.Event(),
                        event,
                        {
                            type: type,
                            isSimulated: true
                        }
                    );

                    jQuery.event.trigger( e, null, elem );
                }

            } );

            jQuery.fn.extend( {

                trigger: function( type, data ) {
                    return this.each( function() {
                        jQuery.event.trigger( type, data, this );
                    } );
                },
                triggerHandler: function( type, data ) {
                    var elem = this[ 0 ];
                    if ( elem ) {
                        return jQuery.event.trigger( type, data, elem, true );
                    }
                }
            } );


// Support: Firefox <=44
// Firefox doesn't have focus(in | out) events
// Related ticket - https://bugzilla.mozilla.org/show_bug.cgi?id=687787
//
// Support: Chrome <=48 - 49, Safari <=9.0 - 9.1
// focus(in | out) events fire after focus & blur events,
// which is spec violation - http://www.w3.org/TR/DOM-Level-3-Events/#events-focusevent-event-order
// Related ticket - https://bugs.chromium.org/p/chromium/issues/detail?id=449857
            if ( !support.focusin ) {
                jQuery.each( { focus: "focusin", blur: "focusout" }, function( orig, fix ) {

                    // Attach a single capturing handler on the document while someone wants focusin/focusout
                    var handler = function( event ) {
                        jQuery.event.simulate( fix, event.target, jQuery.event.fix( event ) );
                    };

                    jQuery.event.special[ fix ] = {
                        setup: function() {

                            // Handle: regular nodes (via `this.ownerDocument`), window
                            // (via `this.document`) & document (via `this`).
                            var doc = this.ownerDocument || this.document || this,
                                attaches = dataPriv.access( doc, fix );

                            if ( !attaches ) {
                                doc.addEventListener( orig, handler, true );
                            }
                            dataPriv.access( doc, fix, ( attaches || 0 ) + 1 );
                        },
                        teardown: function() {
                            var doc = this.ownerDocument || this.document || this,
                                attaches = dataPriv.access( doc, fix ) - 1;

                            if ( !attaches ) {
                                doc.removeEventListener( orig, handler, true );
                                dataPriv.remove( doc, fix );

                            } else {
                                dataPriv.access( doc, fix, attaches );
                            }
                        }
                    };
                } );
            }
            var location = window.location;

            var nonce = { guid: Date.now() };

            var rquery = ( /\?/ );



// Cross-browser xml parsing
            jQuery.parseXML = function( data ) {
                var xml;
                if ( !data || typeof data !== "string" ) {
                    return null;
                }

                // Support: IE 9 - 11 only
                // IE throws on parseFromString with invalid input.
                try {
                    xml = ( new window.DOMParser() ).parseFromString( data, "text/xml" );
                } catch ( e ) {
                    xml = undefined;
                }

                if ( !xml || xml.getElementsByTagName( "parsererror" ).length ) {
                    jQuery.error( "Invalid XML: " + data );
                }
                return xml;
            };


            var
                rbracket = /\[\]$/,
                rCRLF = /\r?\n/g,
                rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
                rsubmittable = /^(?:input|select|textarea|keygen)/i;

            function buildParams( prefix, obj, traditional, add ) {
                var name;

                if ( Array.isArray( obj ) ) {

                    // Serialize array item.
                    jQuery.each( obj, function( i, v ) {
                        if ( traditional || rbracket.test( prefix ) ) {

                            // Treat each array item as a scalar.
                            add( prefix, v );

                        } else {

                            // Item is non-scalar (array or object), encode its numeric index.
                            buildParams(
                                prefix + "[" + ( typeof v === "object" && v != null ? i : "" ) + "]",
                                v,
                                traditional,
                                add
                            );
                        }
                    } );

                } else if ( !traditional && toType( obj ) === "object" ) {

                    // Serialize object item.
                    for ( name in obj ) {
                        buildParams( prefix + "[" + name + "]", obj[ name ], traditional, add );
                    }

                } else {

                    // Serialize scalar item.
                    add( prefix, obj );
                }
            }

// Serialize an array of form elements or a set of
// key/values into a query string
            jQuery.param = function( a, traditional ) {
                var prefix,
                    s = [],
                    add = function( key, valueOrFunction ) {

                        // If value is a function, invoke it and use its return value
                        var value = isFunction( valueOrFunction ) ?
                            valueOrFunction() :
                            valueOrFunction;

                        s[ s.length ] = encodeURIComponent( key ) + "=" +
                            encodeURIComponent( value == null ? "" : value );
                    };

                if ( a == null ) {
                    return "";
                }

                // If an array was passed in, assume that it is an array of form elements.
                if ( Array.isArray( a ) || ( a.jquery && !jQuery.isPlainObject( a ) ) ) {

                    // Serialize the form elements
                    jQuery.each( a, function() {
                        add( this.name, this.value );
                    } );

                } else {

                    // If traditional, encode the "old" way (the way 1.3.2 or older
                    // did it), otherwise encode params recursively.
                    for ( prefix in a ) {
                        buildParams( prefix, a[ prefix ], traditional, add );
                    }
                }

                // Return the resulting serialization
                return s.join( "&" );
            };

            jQuery.fn.extend( {
                serialize: function() {
                    return jQuery.param( this.serializeArray() );
                },
                serializeArray: function() {
                    return this.map( function() {

                        // Can add propHook for "elements" to filter or add form elements
                        var elements = jQuery.prop( this, "elements" );
                        return elements ? jQuery.makeArray( elements ) : this;
                    } )
                        .filter( function() {
                            var type = this.type;

                            // Use .is( ":disabled" ) so that fieldset[disabled] works
                            return this.name && !jQuery( this ).is( ":disabled" ) &&
                                rsubmittable.test( this.nodeName ) && !rsubmitterTypes.test( type ) &&
                                ( this.checked || !rcheckableType.test( type ) );
                        } )
                        .map( function( _i, elem ) {
                            var val = jQuery( this ).val();

                            if ( val == null ) {
                                return null;
                            }

                            if ( Array.isArray( val ) ) {
                                return jQuery.map( val, function( val ) {
                                    return { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
                                } );
                            }

                            return { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
                        } ).get();
                }
            } );


            var
                r20 = /%20/g,
                rhash = /#.*$/,
                rantiCache = /([?&])_=[^&]*/,
                rheaders = /^(.*?):[ \t]*([^\r\n]*)$/mg,

                // #7653, #8125, #8152: local protocol detection
                rlocalProtocol = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
                rnoContent = /^(?:GET|HEAD)$/,
                rprotocol = /^\/\//,

                /* Prefilters
	 * 1) They are useful to introduce custom dataTypes (see ajax/jsonp.js for an example)
	 * 2) These are called:
	 *    - BEFORE asking for a transport
	 *    - AFTER param serialization (s.data is a string if s.processData is true)
	 * 3) key is the dataType
	 * 4) the catchall symbol "*" can be used
	 * 5) execution will start with transport dataType and THEN continue down to "*" if needed
	 */
                prefilters = {},

                /* Transports bindings
	 * 1) key is the dataType
	 * 2) the catchall symbol "*" can be used
	 * 3) selection will start with transport dataType and THEN go to "*" if needed
	 */
                transports = {},

                // Avoid comment-prolog char sequence (#10098); must appease lint and evade compression
                allTypes = "*/".concat( "*" ),

                // Anchor tag for parsing the document origin
                originAnchor = document.createElement( "a" );
            originAnchor.href = location.href;

// Base "constructor" for jQuery.ajaxPrefilter and jQuery.ajaxTransport
            function addToPrefiltersOrTransports( structure ) {

                // dataTypeExpression is optional and defaults to "*"
                return function( dataTypeExpression, func ) {

                    if ( typeof dataTypeExpression !== "string" ) {
                        func = dataTypeExpression;
                        dataTypeExpression = "*";
                    }

                    var dataType,
                        i = 0,
                        dataTypes = dataTypeExpression.toLowerCase().match( rnothtmlwhite ) || [];

                    if ( isFunction( func ) ) {

                        // For each dataType in the dataTypeExpression
                        while ( ( dataType = dataTypes[ i++ ] ) ) {

                            // Prepend if requested
                            if ( dataType[ 0 ] === "+" ) {
                                dataType = dataType.slice( 1 ) || "*";
                                ( structure[ dataType ] = structure[ dataType ] || [] ).unshift( func );

                                // Otherwise append
                            } else {
                                ( structure[ dataType ] = structure[ dataType ] || [] ).push( func );
                            }
                        }
                    }
                };
            }

// Base inspection function for prefilters and transports
            function inspectPrefiltersOrTransports( structure, options, originalOptions, jqXHR ) {

                var inspected = {},
                    seekingTransport = ( structure === transports );

                function inspect( dataType ) {
                    var selected;
                    inspected[ dataType ] = true;
                    jQuery.each( structure[ dataType ] || [], function( _, prefilterOrFactory ) {
                        var dataTypeOrTransport = prefilterOrFactory( options, originalOptions, jqXHR );
                        if ( typeof dataTypeOrTransport === "string" &&
                            !seekingTransport && !inspected[ dataTypeOrTransport ] ) {

                            options.dataTypes.unshift( dataTypeOrTransport );
                            inspect( dataTypeOrTransport );
                            return false;
                        } else if ( seekingTransport ) {
                            return !( selected = dataTypeOrTransport );
                        }
                    } );
                    return selected;
                }

                return inspect( options.dataTypes[ 0 ] ) || !inspected[ "*" ] && inspect( "*" );
            }

// A special extend for ajax options
// that takes "flat" options (not to be deep extended)
// Fixes #9887
            function ajaxExtend( target, src ) {
                var key, deep,
                    flatOptions = jQuery.ajaxSettings.flatOptions || {};

                for ( key in src ) {
                    if ( src[ key ] !== undefined ) {
                        ( flatOptions[ key ] ? target : ( deep || ( deep = {} ) ) )[ key ] = src[ key ];
                    }
                }
                if ( deep ) {
                    jQuery.extend( true, target, deep );
                }

                return target;
            }

            /* Handles responses to an ajax request:
 * - finds the right dataType (mediates between content-type and expected dataType)
 * - returns the corresponding response
 */
            function ajaxHandleResponses( s, jqXHR, responses ) {

                var ct, type, finalDataType, firstDataType,
                    contents = s.contents,
                    dataTypes = s.dataTypes;

                // Remove auto dataType and get content-type in the process
                while ( dataTypes[ 0 ] === "*" ) {
                    dataTypes.shift();
                    if ( ct === undefined ) {
                        ct = s.mimeType || jqXHR.getResponseHeader( "Content-Type" );
                    }
                }

                // Check if we're dealing with a known content-type
                if ( ct ) {
                    for ( type in contents ) {
                        if ( contents[ type ] && contents[ type ].test( ct ) ) {
                            dataTypes.unshift( type );
                            break;
                        }
                    }
                }

                // Check to see if we have a response for the expected dataType
                if ( dataTypes[ 0 ] in responses ) {
                    finalDataType = dataTypes[ 0 ];
                } else {

                    // Try convertible dataTypes
                    for ( type in responses ) {
                        if ( !dataTypes[ 0 ] || s.converters[ type + " " + dataTypes[ 0 ] ] ) {
                            finalDataType = type;
                            break;
                        }
                        if ( !firstDataType ) {
                            firstDataType = type;
                        }
                    }

                    // Or just use first one
                    finalDataType = finalDataType || firstDataType;
                }

                // If we found a dataType
                // We add the dataType to the list if needed
                // and return the corresponding response
                if ( finalDataType ) {
                    if ( finalDataType !== dataTypes[ 0 ] ) {
                        dataTypes.unshift( finalDataType );
                    }
                    return responses[ finalDataType ];
                }
            }

            /* Chain conversions given the request and the original response
 * Also sets the responseXXX fields on the jqXHR instance
 */
            function ajaxConvert( s, response, jqXHR, isSuccess ) {
                var conv2, current, conv, tmp, prev,
                    converters = {},

                    // Work with a copy of dataTypes in case we need to modify it for conversion
                    dataTypes = s.dataTypes.slice();

                // Create converters map with lowercased keys
                if ( dataTypes[ 1 ] ) {
                    for ( conv in s.converters ) {
                        converters[ conv.toLowerCase() ] = s.converters[ conv ];
                    }
                }

                current = dataTypes.shift();

                // Convert to each sequential dataType
                while ( current ) {

                    if ( s.responseFields[ current ] ) {
                        jqXHR[ s.responseFields[ current ] ] = response;
                    }

                    // Apply the dataFilter if provided
                    if ( !prev && isSuccess && s.dataFilter ) {
                        response = s.dataFilter( response, s.dataType );
                    }

                    prev = current;
                    current = dataTypes.shift();

                    if ( current ) {

                        // There's only work to do if current dataType is non-auto
                        if ( current === "*" ) {

                            current = prev;

                            // Convert response if prev dataType is non-auto and differs from current
                        } else if ( prev !== "*" && prev !== current ) {

                            // Seek a direct converter
                            conv = converters[ prev + " " + current ] || converters[ "* " + current ];

                            // If none found, seek a pair
                            if ( !conv ) {
                                for ( conv2 in converters ) {

                                    // If conv2 outputs current
                                    tmp = conv2.split( " " );
                                    if ( tmp[ 1 ] === current ) {

                                        // If prev can be converted to accepted input
                                        conv = converters[ prev + " " + tmp[ 0 ] ] ||
                                            converters[ "* " + tmp[ 0 ] ];
                                        if ( conv ) {

                                            // Condense equivalence converters
                                            if ( conv === true ) {
                                                conv = converters[ conv2 ];

                                                // Otherwise, insert the intermediate dataType
                                            } else if ( converters[ conv2 ] !== true ) {
                                                current = tmp[ 0 ];
                                                dataTypes.unshift( tmp[ 1 ] );
                                            }
                                            break;
                                        }
                                    }
                                }
                            }

                            // Apply converter (if not an equivalence)
                            if ( conv !== true ) {

                                // Unless errors are allowed to bubble, catch and return them
                                if ( conv && s.throws ) {
                                    response = conv( response );
                                } else {
                                    try {
                                        response = conv( response );
                                    } catch ( e ) {
                                        return {
                                            state: "parsererror",
                                            error: conv ? e : "No conversion from " + prev + " to " + current
                                        };
                                    }
                                }
                            }
                        }
                    }
                }

                return { state: "success", data: response };
            }

            jQuery.extend( {

                // Counter for holding the number of active queries
                active: 0,

                // Last-Modified header cache for next request
                lastModified: {},
                etag: {},

                ajaxSettings: {
                    url: location.href,
                    type: "GET",
                    isLocal: rlocalProtocol.test( location.protocol ),
                    global: true,
                    processData: true,
                    async: true,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",

                    /*
		timeout: 0,
		data: null,
		dataType: null,
		username: null,
		password: null,
		cache: null,
		throws: false,
		traditional: false,
		headers: {},
		*/

                    accepts: {
                        "*": allTypes,
                        text: "text/plain",
                        html: "text/html",
                        xml: "application/xml, text/xml",
                        json: "application/json, text/javascript"
                    },

                    contents: {
                        xml: /\bxml\b/,
                        html: /\bhtml/,
                        json: /\bjson\b/
                    },

                    responseFields: {
                        xml: "responseXML",
                        text: "responseText",
                        json: "responseJSON"
                    },

                    // Data converters
                    // Keys separate source (or catchall "*") and destination types with a single space
                    converters: {

                        // Convert anything to text
                        "* text": String,

                        // Text to html (true = no transformation)
                        "text html": true,

                        // Evaluate text as a json expression
                        "text json": JSON.parse,

                        // Parse text as xml
                        "text xml": jQuery.parseXML
                    },

                    // For options that shouldn't be deep extended:
                    // you can add your own custom options here if
                    // and when you create one that shouldn't be
                    // deep extended (see ajaxExtend)
                    flatOptions: {
                        url: true,
                        context: true
                    }
                },

                // Creates a full fledged settings object into target
                // with both ajaxSettings and settings fields.
                // If target is omitted, writes into ajaxSettings.
                ajaxSetup: function( target, settings ) {
                    return settings ?

                        // Building a settings object
                        ajaxExtend( ajaxExtend( target, jQuery.ajaxSettings ), settings ) :

                        // Extending ajaxSettings
                        ajaxExtend( jQuery.ajaxSettings, target );
                },

                ajaxPrefilter: addToPrefiltersOrTransports( prefilters ),
                ajaxTransport: addToPrefiltersOrTransports( transports ),

                // Main method
                ajax: function( url, options ) {

                    // If url is an object, simulate pre-1.5 signature
                    if ( typeof url === "object" ) {
                        options = url;
                        url = undefined;
                    }

                    // Force options to be an object
                    options = options || {};

                    var transport,

                        // URL without anti-cache param
                        cacheURL,

                        // Response headers
                        responseHeadersString,
                        responseHeaders,

                        // timeout handle
                        timeoutTimer,

                        // Url cleanup var
                        urlAnchor,

                        // Request state (becomes false upon send and true upon completion)
                        completed,

                        // To know if global events are to be dispatched
                        fireGlobals,

                        // Loop variable
                        i,

                        // uncached part of the url
                        uncached,

                        // Create the final options object
                        s = jQuery.ajaxSetup( {}, options ),

                        // Callbacks context
                        callbackContext = s.context || s,

                        // Context for global events is callbackContext if it is a DOM node or jQuery collection
                        globalEventContext = s.context &&
                        ( callbackContext.nodeType || callbackContext.jquery ) ?
                            jQuery( callbackContext ) :
                            jQuery.event,

                        // Deferreds
                        deferred = jQuery.Deferred(),
                        completeDeferred = jQuery.Callbacks( "once memory" ),

                        // Status-dependent callbacks
                        statusCode = s.statusCode || {},

                        // Headers (they are sent all at once)
                        requestHeaders = {},
                        requestHeadersNames = {},

                        // Default abort message
                        strAbort = "canceled",

                        // Fake xhr
                        jqXHR = {
                            readyState: 0,

                            // Builds headers hashtable if needed
                            getResponseHeader: function( key ) {
                                var match;
                                if ( completed ) {
                                    if ( !responseHeaders ) {
                                        responseHeaders = {};
                                        while ( ( match = rheaders.exec( responseHeadersString ) ) ) {
                                            responseHeaders[ match[ 1 ].toLowerCase() + " " ] =
                                                ( responseHeaders[ match[ 1 ].toLowerCase() + " " ] || [] )
                                                    .concat( match[ 2 ] );
                                        }
                                    }
                                    match = responseHeaders[ key.toLowerCase() + " " ];
                                }
                                return match == null ? null : match.join( ", " );
                            },

                            // Raw string
                            getAllResponseHeaders: function() {
                                return completed ? responseHeadersString : null;
                            },

                            // Caches the header
                            setRequestHeader: function( name, value ) {
                                if ( completed == null ) {
                                    name = requestHeadersNames[ name.toLowerCase() ] =
                                        requestHeadersNames[ name.toLowerCase() ] || name;
                                    requestHeaders[ name ] = value;
                                }
                                return this;
                            },

                            // Overrides response content-type header
                            overrideMimeType: function( type ) {
                                if ( completed == null ) {
                                    s.mimeType = type;
                                }
                                return this;
                            },

                            // Status-dependent callbacks
                            statusCode: function( map ) {
                                var code;
                                if ( map ) {
                                    if ( completed ) {

                                        // Execute the appropriate callbacks
                                        jqXHR.always( map[ jqXHR.status ] );
                                    } else {

                                        // Lazy-add the new callbacks in a way that preserves old ones
                                        for ( code in map ) {
                                            statusCode[ code ] = [ statusCode[ code ], map[ code ] ];
                                        }
                                    }
                                }
                                return this;
                            },

                            // Cancel the request
                            abort: function( statusText ) {
                                var finalText = statusText || strAbort;
                                if ( transport ) {
                                    transport.abort( finalText );
                                }
                                done( 0, finalText );
                                return this;
                            }
                        };

                    // Attach deferreds
                    deferred.promise( jqXHR );

                    // Add protocol if not provided (prefilters might expect it)
                    // Handle falsy url in the settings object (#10093: consistency with old signature)
                    // We also use the url parameter if available
                    s.url = ( ( url || s.url || location.href ) + "" )
                        .replace( rprotocol, location.protocol + "//" );

                    // Alias method option to type as per ticket #12004
                    s.type = options.method || options.type || s.method || s.type;

                    // Extract dataTypes list
                    s.dataTypes = ( s.dataType || "*" ).toLowerCase().match( rnothtmlwhite ) || [ "" ];

                    // A cross-domain request is in order when the origin doesn't match the current origin.
                    if ( s.crossDomain == null ) {
                        urlAnchor = document.createElement( "a" );

                        // Support: IE <=8 - 11, Edge 12 - 15
                        // IE throws exception on accessing the href property if url is malformed,
                        // e.g. http://example.com:80x/
                        try {
                            urlAnchor.href = s.url;

                            // Support: IE <=8 - 11 only
                            // Anchor's host property isn't correctly set when s.url is relative
                            urlAnchor.href = urlAnchor.href;
                            s.crossDomain = originAnchor.protocol + "//" + originAnchor.host !==
                                urlAnchor.protocol + "//" + urlAnchor.host;
                        } catch ( e ) {

                            // If there is an error parsing the URL, assume it is crossDomain,
                            // it can be rejected by the transport if it is invalid
                            s.crossDomain = true;
                        }
                    }

                    // Convert data if not already a string
                    if ( s.data && s.processData && typeof s.data !== "string" ) {
                        s.data = jQuery.param( s.data, s.traditional );
                    }

                    // Apply prefilters
                    inspectPrefiltersOrTransports( prefilters, s, options, jqXHR );

                    // If request was aborted inside a prefilter, stop there
                    if ( completed ) {
                        return jqXHR;
                    }

                    // We can fire global events as of now if asked to
                    // Don't fire events if jQuery.event is undefined in an AMD-usage scenario (#15118)
                    fireGlobals = jQuery.event && s.global;

                    // Watch for a new set of requests
                    if ( fireGlobals && jQuery.active++ === 0 ) {
                        jQuery.event.trigger( "ajaxStart" );
                    }

                    // Uppercase the type
                    s.type = s.type.toUpperCase();

                    // Determine if request has content
                    s.hasContent = !rnoContent.test( s.type );

                    // Save the URL in case we're toying with the If-Modified-Since
                    // and/or If-None-Match header later on
                    // Remove hash to simplify url manipulation
                    cacheURL = s.url.replace( rhash, "" );

                    // More options handling for requests with no content
                    if ( !s.hasContent ) {

                        // Remember the hash so we can put it back
                        uncached = s.url.slice( cacheURL.length );

                        // If data is available and should be processed, append data to url
                        if ( s.data && ( s.processData || typeof s.data === "string" ) ) {
                            cacheURL += ( rquery.test( cacheURL ) ? "&" : "?" ) + s.data;

                            // #9682: remove data so that it's not used in an eventual retry
                            delete s.data;
                        }

                        // Add or update anti-cache param if needed
                        if ( s.cache === false ) {
                            cacheURL = cacheURL.replace( rantiCache, "$1" );
                            uncached = ( rquery.test( cacheURL ) ? "&" : "?" ) + "_=" + ( nonce.guid++ ) +
                                uncached;
                        }

                        // Put hash and anti-cache on the URL that will be requested (gh-1732)
                        s.url = cacheURL + uncached;

                        // Change '%20' to '+' if this is encoded form body content (gh-2658)
                    } else if ( s.data && s.processData &&
                        ( s.contentType || "" ).indexOf( "application/x-www-form-urlencoded" ) === 0 ) {
                        s.data = s.data.replace( r20, "+" );
                    }

                    // Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
                    if ( s.ifModified ) {
                        if ( jQuery.lastModified[ cacheURL ] ) {
                            jqXHR.setRequestHeader( "If-Modified-Since", jQuery.lastModified[ cacheURL ] );
                        }
                        if ( jQuery.etag[ cacheURL ] ) {
                            jqXHR.setRequestHeader( "If-None-Match", jQuery.etag[ cacheURL ] );
                        }
                    }

                    // Set the correct header, if data is being sent
                    if ( s.data && s.hasContent && s.contentType !== false || options.contentType ) {
                        jqXHR.setRequestHeader( "Content-Type", s.contentType );
                    }

                    // Set the Accepts header for the server, depending on the dataType
                    jqXHR.setRequestHeader(
                        "Accept",
                        s.dataTypes[ 0 ] && s.accepts[ s.dataTypes[ 0 ] ] ?
                            s.accepts[ s.dataTypes[ 0 ] ] +
                            ( s.dataTypes[ 0 ] !== "*" ? ", " + allTypes + "; q=0.01" : "" ) :
                            s.accepts[ "*" ]
                    );

                    // Check for headers option
                    for ( i in s.headers ) {
                        jqXHR.setRequestHeader( i, s.headers[ i ] );
                    }

                    // Allow custom headers/mimetypes and early abort
                    if ( s.beforeSend &&
                        ( s.beforeSend.call( callbackContext, jqXHR, s ) === false || completed ) ) {

                        // Abort if not done already and return
                        return jqXHR.abort();
                    }

                    // Aborting is no longer a cancellation
                    strAbort = "abort";

                    // Install callbacks on deferreds
                    completeDeferred.add( s.complete );
                    jqXHR.done( s.success );
                    jqXHR.fail( s.error );

                    // Get transport
                    transport = inspectPrefiltersOrTransports( transports, s, options, jqXHR );

                    // If no transport, we auto-abort
                    if ( !transport ) {
                        done( -1, "No Transport" );
                    } else {
                        jqXHR.readyState = 1;

                        // Send global event
                        if ( fireGlobals ) {
                            globalEventContext.trigger( "ajaxSend", [ jqXHR, s ] );
                        }

                        // If request was aborted inside ajaxSend, stop there
                        if ( completed ) {
                            return jqXHR;
                        }

                        // Timeout
                        if ( s.async && s.timeout > 0 ) {
                            timeoutTimer = window.setTimeout( function() {
                                jqXHR.abort( "timeout" );
                            }, s.timeout );
                        }

                        try {
                            completed = false;
                            transport.send( requestHeaders, done );
                        } catch ( e ) {

                            // Rethrow post-completion exceptions
                            if ( completed ) {
                                throw e;
                            }

                            // Propagate others as results
                            done( -1, e );
                        }
                    }

                    // Callback for when everything is done
                    function done( status, nativeStatusText, responses, headers ) {
                        var isSuccess, success, error, response, modified,
                            statusText = nativeStatusText;

                        // Ignore repeat invocations
                        if ( completed ) {
                            return;
                        }

                        completed = true;

                        // Clear timeout if it exists
                        if ( timeoutTimer ) {
                            window.clearTimeout( timeoutTimer );
                        }

                        // Dereference transport for early garbage collection
                        // (no matter how long the jqXHR object will be used)
                        transport = undefined;

                        // Cache response headers
                        responseHeadersString = headers || "";

                        // Set readyState
                        jqXHR.readyState = status > 0 ? 4 : 0;

                        // Determine if successful
                        isSuccess = status >= 200 && status < 300 || status === 304;

                        // Get response data
                        if ( responses ) {
                            response = ajaxHandleResponses( s, jqXHR, responses );
                        }

                        // Use a noop converter for missing script
                        if ( !isSuccess && jQuery.inArray( "script", s.dataTypes ) > -1 ) {
                            s.converters[ "text script" ] = function() {};
                        }

                        // Convert no matter what (that way responseXXX fields are always set)
                        response = ajaxConvert( s, response, jqXHR, isSuccess );

                        // If successful, handle type chaining
                        if ( isSuccess ) {

                            // Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
                            if ( s.ifModified ) {
                                modified = jqXHR.getResponseHeader( "Last-Modified" );
                                if ( modified ) {
                                    jQuery.lastModified[ cacheURL ] = modified;
                                }
                                modified = jqXHR.getResponseHeader( "etag" );
                                if ( modified ) {
                                    jQuery.etag[ cacheURL ] = modified;
                                }
                            }

                            // if no content
                            if ( status === 204 || s.type === "HEAD" ) {
                                statusText = "nocontent";

                                // if not modified
                            } else if ( status === 304 ) {
                                statusText = "notmodified";

                                // If we have data, let's convert it
                            } else {
                                statusText = response.state;
                                success = response.data;
                                error = response.error;
                                isSuccess = !error;
                            }
                        } else {

                            // Extract error from statusText and normalize for non-aborts
                            error = statusText;
                            if ( status || !statusText ) {
                                statusText = "error";
                                if ( status < 0 ) {
                                    status = 0;
                                }
                            }
                        }

                        // Set data for the fake xhr object
                        jqXHR.status = status;
                        jqXHR.statusText = ( nativeStatusText || statusText ) + "";

                        // Success/Error
                        if ( isSuccess ) {
                            deferred.resolveWith( callbackContext, [ success, statusText, jqXHR ] );
                        } else {
                            deferred.rejectWith( callbackContext, [ jqXHR, statusText, error ] );
                        }

                        // Status-dependent callbacks
                        jqXHR.statusCode( statusCode );
                        statusCode = undefined;

                        if ( fireGlobals ) {
                            globalEventContext.trigger( isSuccess ? "ajaxSuccess" : "ajaxError",
                                [ jqXHR, s, isSuccess ? success : error ] );
                        }

                        // Complete
                        completeDeferred.fireWith( callbackContext, [ jqXHR, statusText ] );

                        if ( fireGlobals ) {
                            globalEventContext.trigger( "ajaxComplete", [ jqXHR, s ] );

                            // Handle the global AJAX counter
                            if ( !( --jQuery.active ) ) {
                                jQuery.event.trigger( "ajaxStop" );
                            }
                        }
                    }

                    return jqXHR;
                },

                getJSON: function( url, data, callback ) {
                    return jQuery.get( url, data, callback, "json" );
                },

                getScript: function( url, callback ) {
                    return jQuery.get( url, undefined, callback, "script" );
                }
            } );

            jQuery.each( [ "get", "post" ], function( _i, method ) {
                jQuery[ method ] = function( url, data, callback, type ) {

                    // Shift arguments if data argument was omitted
                    if ( isFunction( data ) ) {
                        type = type || callback;
                        callback = data;
                        data = undefined;
                    }

                    // The url can be an options object (which then must have .url)
                    return jQuery.ajax( jQuery.extend( {
                        url: url,
                        type: method,
                        dataType: type,
                        data: data,
                        success: callback
                    }, jQuery.isPlainObject( url ) && url ) );
                };
            } );

            jQuery.ajaxPrefilter( function( s ) {
                var i;
                for ( i in s.headers ) {
                    if ( i.toLowerCase() === "content-type" ) {
                        s.contentType = s.headers[ i ] || "";
                    }
                }
            } );


            jQuery._evalUrl = function( url, options, doc ) {
                return jQuery.ajax( {
                    url: url,

                    // Make this explicit, since user can override this through ajaxSetup (#11264)
                    type: "GET",
                    dataType: "script",
                    cache: true,
                    async: false,
                    global: false,

                    // Only evaluate the response if it is successful (gh-4126)
                    // dataFilter is not invoked for failure responses, so using it instead
                    // of the default converter is kludgy but it works.
                    converters: {
                        "text script": function() {}
                    },
                    dataFilter: function( response ) {
                        jQuery.globalEval( response, options, doc );
                    }
                } );
            };


            jQuery.fn.extend( {
                wrapAll: function( html ) {
                    var wrap;

                    if ( this[ 0 ] ) {
                        if ( isFunction( html ) ) {
                            html = html.call( this[ 0 ] );
                        }

                        // The elements to wrap the target around
                        wrap = jQuery( html, this[ 0 ].ownerDocument ).eq( 0 ).clone( true );

                        if ( this[ 0 ].parentNode ) {
                            wrap.insertBefore( this[ 0 ] );
                        }

                        wrap.map( function() {
                            var elem = this;

                            while ( elem.firstElementChild ) {
                                elem = elem.firstElementChild;
                            }

                            return elem;
                        } ).append( this );
                    }

                    return this;
                },

                wrapInner: function( html ) {
                    if ( isFunction( html ) ) {
                        return this.each( function( i ) {
                            jQuery( this ).wrapInner( html.call( this, i ) );
                        } );
                    }

                    return this.each( function() {
                        var self = jQuery( this ),
                            contents = self.contents();

                        if ( contents.length ) {
                            contents.wrapAll( html );

                        } else {
                            self.append( html );
                        }
                    } );
                },

                wrap: function( html ) {
                    var htmlIsFunction = isFunction( html );

                    return this.each( function( i ) {
                        jQuery( this ).wrapAll( htmlIsFunction ? html.call( this, i ) : html );
                    } );
                },

                unwrap: function( selector ) {
                    this.parent( selector ).not( "body" ).each( function() {
                        jQuery( this ).replaceWith( this.childNodes );
                    } );
                    return this;
                }
            } );


            jQuery.expr.pseudos.hidden = function( elem ) {
                return !jQuery.expr.pseudos.visible( elem );
            };
            jQuery.expr.pseudos.visible = function( elem ) {
                return !!( elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length );
            };




            jQuery.ajaxSettings.xhr = function() {
                try {
                    return new window.XMLHttpRequest();
                } catch ( e ) {}
            };

            var xhrSuccessStatus = {

                    // File protocol always yields status code 0, assume 200
                    0: 200,

                    // Support: IE <=9 only
                    // #1450: sometimes IE returns 1223 when it should be 204
                    1223: 204
                },
                xhrSupported = jQuery.ajaxSettings.xhr();

            support.cors = !!xhrSupported && ( "withCredentials" in xhrSupported );
            support.ajax = xhrSupported = !!xhrSupported;

            jQuery.ajaxTransport( function( options ) {
                var callback, errorCallback;

                // Cross domain only allowed if supported through XMLHttpRequest
                if ( support.cors || xhrSupported && !options.crossDomain ) {
                    return {
                        send: function( headers, complete ) {
                            var i,
                                xhr = options.xhr();

                            xhr.open(
                                options.type,
                                options.url,
                                options.async,
                                options.username,
                                options.password
                            );

                            // Apply custom fields if provided
                            if ( options.xhrFields ) {
                                for ( i in options.xhrFields ) {
                                    xhr[ i ] = options.xhrFields[ i ];
                                }
                            }

                            // Override mime type if needed
                            if ( options.mimeType && xhr.overrideMimeType ) {
                                xhr.overrideMimeType( options.mimeType );
                            }

                            // X-Requested-With header
                            // For cross-domain requests, seeing as conditions for a preflight are
                            // akin to a jigsaw puzzle, we simply never set it to be sure.
                            // (it can always be set on a per-request basis or even using ajaxSetup)
                            // For same-domain requests, won't change header if already provided.
                            if ( !options.crossDomain && !headers[ "X-Requested-With" ] ) {
                                headers[ "X-Requested-With" ] = "XMLHttpRequest";
                            }

                            // Set headers
                            for ( i in headers ) {
                                xhr.setRequestHeader( i, headers[ i ] );
                            }

                            // Callback
                            callback = function( type ) {
                                return function() {
                                    if ( callback ) {
                                        callback = errorCallback = xhr.onload =
                                            xhr.onerror = xhr.onabort = xhr.ontimeout =
                                                xhr.onreadystatechange = null;

                                        if ( type === "abort" ) {
                                            xhr.abort();
                                        } else if ( type === "error" ) {

                                            // Support: IE <=9 only
                                            // On a manual native abort, IE9 throws
                                            // errors on any property access that is not readyState
                                            if ( typeof xhr.status !== "number" ) {
                                                complete( 0, "error" );
                                            } else {
                                                complete(

                                                    // File: protocol always yields status 0; see #8605, #14207
                                                    xhr.status,
                                                    xhr.statusText
                                                );
                                            }
                                        } else {
                                            complete(
                                                xhrSuccessStatus[ xhr.status ] || xhr.status,
                                                xhr.statusText,

                                                // Support: IE <=9 only
                                                // IE9 has no XHR2 but throws on binary (trac-11426)
                                                // For XHR2 non-text, let the caller handle it (gh-2498)
                                                ( xhr.responseType || "text" ) !== "text"  ||
                                                typeof xhr.responseText !== "string" ?
                                                    { binary: xhr.response } :
                                                    { text: xhr.responseText },
                                                xhr.getAllResponseHeaders()
                                            );
                                        }
                                    }
                                };
                            };

                            // Listen to events
                            xhr.onload = callback();
                            errorCallback = xhr.onerror = xhr.ontimeout = callback( "error" );

                            // Support: IE 9 only
                            // Use onreadystatechange to replace onabort
                            // to handle uncaught aborts
                            if ( xhr.onabort !== undefined ) {
                                xhr.onabort = errorCallback;
                            } else {
                                xhr.onreadystatechange = function() {

                                    // Check readyState before timeout as it changes
                                    if ( xhr.readyState === 4 ) {

                                        // Allow onerror to be called first,
                                        // but that will not handle a native abort
                                        // Also, save errorCallback to a variable
                                        // as xhr.onerror cannot be accessed
                                        window.setTimeout( function() {
                                            if ( callback ) {
                                                errorCallback();
                                            }
                                        } );
                                    }
                                };
                            }

                            // Create the abort callback
                            callback = callback( "abort" );

                            try {

                                // Do send the request (this may raise an exception)
                                xhr.send( options.hasContent && options.data || null );
                            } catch ( e ) {

                                // #14683: Only rethrow if this hasn't been notified as an error yet
                                if ( callback ) {
                                    throw e;
                                }
                            }
                        },

                        abort: function() {
                            if ( callback ) {
                                callback();
                            }
                        }
                    };
                }
            } );




// Prevent auto-execution of scripts when no explicit dataType was provided (See gh-2432)
            jQuery.ajaxPrefilter( function( s ) {
                if ( s.crossDomain ) {
                    s.contents.script = false;
                }
            } );

// Install script dataType
            jQuery.ajaxSetup( {
                accepts: {
                    script: "text/javascript, application/javascript, " +
                        "application/ecmascript, application/x-ecmascript"
                },
                contents: {
                    script: /\b(?:java|ecma)script\b/
                },
                converters: {
                    "text script": function( text ) {
                        jQuery.globalEval( text );
                        return text;
                    }
                }
            } );

// Handle cache's special case and crossDomain
            jQuery.ajaxPrefilter( "script", function( s ) {
                if ( s.cache === undefined ) {
                    s.cache = false;
                }
                if ( s.crossDomain ) {
                    s.type = "GET";
                }
            } );

// Bind script tag hack transport
            jQuery.ajaxTransport( "script", function( s ) {

                // This transport only deals with cross domain or forced-by-attrs requests
                if ( s.crossDomain || s.scriptAttrs ) {
                    var script, callback;
                    return {
                        send: function( _, complete ) {
                            script = jQuery( "<script>" )
                                .attr( s.scriptAttrs || {} )
                                .prop( { charset: s.scriptCharset, src: s.url } )
                                .on( "load error", callback = function( evt ) {
                                    script.remove();
                                    callback = null;
                                    if ( evt ) {
                                        complete( evt.type === "error" ? 404 : 200, evt.type );
                                    }
                                } );

                            // Use native DOM manipulation to avoid our domManip AJAX trickery
                            document.head.appendChild( script[ 0 ] );
                        },
                        abort: function() {
                            if ( callback ) {
                                callback();
                            }
                        }
                    };
                }
            } );




            var oldCallbacks = [],
                rjsonp = /(=)\?(?=&|$)|\?\?/;

// Default jsonp settings
            jQuery.ajaxSetup( {
                jsonp: "callback",
                jsonpCallback: function() {
                    var callback = oldCallbacks.pop() || ( jQuery.expando + "_" + ( nonce.guid++ ) );
                    this[ callback ] = true;
                    return callback;
                }
            } );

// Detect, normalize options and install callbacks for jsonp requests
            jQuery.ajaxPrefilter( "json jsonp", function( s, originalSettings, jqXHR ) {

                var callbackName, overwritten, responseContainer,
                    jsonProp = s.jsonp !== false && ( rjsonp.test( s.url ) ?
                            "url" :
                            typeof s.data === "string" &&
                            ( s.contentType || "" )
                                .indexOf( "application/x-www-form-urlencoded" ) === 0 &&
                            rjsonp.test( s.data ) && "data"
                    );

                // Handle iff the expected data type is "jsonp" or we have a parameter to set
                if ( jsonProp || s.dataTypes[ 0 ] === "jsonp" ) {

                    // Get callback name, remembering preexisting value associated with it
                    callbackName = s.jsonpCallback = isFunction( s.jsonpCallback ) ?
                        s.jsonpCallback() :
                        s.jsonpCallback;

                    // Insert callback into url or form data
                    if ( jsonProp ) {
                        s[ jsonProp ] = s[ jsonProp ].replace( rjsonp, "$1" + callbackName );
                    } else if ( s.jsonp !== false ) {
                        s.url += ( rquery.test( s.url ) ? "&" : "?" ) + s.jsonp + "=" + callbackName;
                    }

                    // Use data converter to retrieve json after script execution
                    s.converters[ "script json" ] = function() {
                        if ( !responseContainer ) {
                            jQuery.error( callbackName + " was not called" );
                        }
                        return responseContainer[ 0 ];
                    };

                    // Force json dataType
                    s.dataTypes[ 0 ] = "json";

                    // Install callback
                    overwritten = window[ callbackName ];
                    window[ callbackName ] = function() {
                        responseContainer = arguments;
                    };

                    // Clean-up function (fires after converters)
                    jqXHR.always( function() {

                        // If previous value didn't exist - remove it
                        if ( overwritten === undefined ) {
                            jQuery( window ).removeProp( callbackName );

                            // Otherwise restore preexisting value
                        } else {
                            window[ callbackName ] = overwritten;
                        }

                        // Save back as free
                        if ( s[ callbackName ] ) {

                            // Make sure that re-using the options doesn't screw things around
                            s.jsonpCallback = originalSettings.jsonpCallback;

                            // Save the callback name for future use
                            oldCallbacks.push( callbackName );
                        }

                        // Call if it was a function and we have a response
                        if ( responseContainer && isFunction( overwritten ) ) {
                            overwritten( responseContainer[ 0 ] );
                        }

                        responseContainer = overwritten = undefined;
                    } );

                    // Delegate to script
                    return "script";
                }
            } );




// Support: Safari 8 only
// In Safari 8 documents created via document.implementation.createHTMLDocument
// collapse sibling forms: the second one becomes a child of the first one.
// Because of that, this security measure has to be disabled in Safari 8.
// https://bugs.webkit.org/show_bug.cgi?id=137337
            support.createHTMLDocument = ( function() {
                var body = document.implementation.createHTMLDocument( "" ).body;
                body.innerHTML = "<form></form><form></form>";
                return body.childNodes.length === 2;
            } )();


// Argument "data" should be string of html
// context (optional): If specified, the fragment will be created in this context,
// defaults to document
// keepScripts (optional): If true, will include scripts passed in the html string
            jQuery.parseHTML = function( data, context, keepScripts ) {
                if ( typeof data !== "string" ) {
                    return [];
                }
                if ( typeof context === "boolean" ) {
                    keepScripts = context;
                    context = false;
                }

                var base, parsed, scripts;

                if ( !context ) {

                    // Stop scripts or inline event handlers from being executed immediately
                    // by using document.implementation
                    if ( support.createHTMLDocument ) {
                        context = document.implementation.createHTMLDocument( "" );

                        // Set the base href for the created document
                        // so any parsed elements with URLs
                        // are based on the document's URL (gh-2965)
                        base = context.createElement( "base" );
                        base.href = document.location.href;
                        context.head.appendChild( base );
                    } else {
                        context = document;
                    }
                }

                parsed = rsingleTag.exec( data );
                scripts = !keepScripts && [];

                // Single tag
                if ( parsed ) {
                    return [ context.createElement( parsed[ 1 ] ) ];
                }

                parsed = buildFragment( [ data ], context, scripts );

                if ( scripts && scripts.length ) {
                    jQuery( scripts ).remove();
                }

                return jQuery.merge( [], parsed.childNodes );
            };


            /**
             * Load a url into a page
             */
            jQuery.fn.load = function( url, params, callback ) {
                var selector, type, response,
                    self = this,
                    off = url.indexOf( " " );

                if ( off > -1 ) {
                    selector = stripAndCollapse( url.slice( off ) );
                    url = url.slice( 0, off );
                }

                // If it's a function
                if ( isFunction( params ) ) {

                    // We assume that it's the callback
                    callback = params;
                    params = undefined;

                    // Otherwise, build a param string
                } else if ( params && typeof params === "object" ) {
                    type = "POST";
                }

                // If we have elements to modify, make the request
                if ( self.length > 0 ) {
                    jQuery.ajax( {
                        url: url,

                        // If "type" variable is undefined, then "GET" method will be used.
                        // Make value of this field explicit since
                        // user can override it through ajaxSetup method
                        type: type || "GET",
                        dataType: "html",
                        data: params
                    } ).done( function( responseText ) {

                        // Save response for use in complete callback
                        response = arguments;

                        self.html( selector ?

                            // If a selector was specified, locate the right elements in a dummy div
                            // Exclude scripts to avoid IE 'Permission Denied' errors
                            jQuery( "<div>" ).append( jQuery.parseHTML( responseText ) ).find( selector ) :

                            // Otherwise use the full result
                            responseText );

                        // If the request succeeds, this function gets "data", "status", "jqXHR"
                        // but they are ignored because response was set above.
                        // If it fails, this function gets "jqXHR", "status", "error"
                    } ).always( callback && function( jqXHR, status ) {
                        self.each( function() {
                            callback.apply( this, response || [ jqXHR.responseText, status, jqXHR ] );
                        } );
                    } );
                }

                return this;
            };




            jQuery.expr.pseudos.animated = function( elem ) {
                return jQuery.grep( jQuery.timers, function( fn ) {
                    return elem === fn.elem;
                } ).length;
            };




            jQuery.offset = {
                setOffset: function( elem, options, i ) {
                    var curPosition, curLeft, curCSSTop, curTop, curOffset, curCSSLeft, calculatePosition,
                        position = jQuery.css( elem, "position" ),
                        curElem = jQuery( elem ),
                        props = {};

                    // Set position first, in-case top/left are set even on static elem
                    if ( position === "static" ) {
                        elem.style.position = "relative";
                    }

                    curOffset = curElem.offset();
                    curCSSTop = jQuery.css( elem, "top" );
                    curCSSLeft = jQuery.css( elem, "left" );
                    calculatePosition = ( position === "absolute" || position === "fixed" ) &&
                        ( curCSSTop + curCSSLeft ).indexOf( "auto" ) > -1;

                    // Need to be able to calculate position if either
                    // top or left is auto and position is either absolute or fixed
                    if ( calculatePosition ) {
                        curPosition = curElem.position();
                        curTop = curPosition.top;
                        curLeft = curPosition.left;

                    } else {
                        curTop = parseFloat( curCSSTop ) || 0;
                        curLeft = parseFloat( curCSSLeft ) || 0;
                    }

                    if ( isFunction( options ) ) {

                        // Use jQuery.extend here to allow modification of coordinates argument (gh-1848)
                        options = options.call( elem, i, jQuery.extend( {}, curOffset ) );
                    }

                    if ( options.top != null ) {
                        props.top = ( options.top - curOffset.top ) + curTop;
                    }
                    if ( options.left != null ) {
                        props.left = ( options.left - curOffset.left ) + curLeft;
                    }

                    if ( "using" in options ) {
                        options.using.call( elem, props );

                    } else {
                        if ( typeof props.top === "number" ) {
                            props.top += "px";
                        }
                        if ( typeof props.left === "number" ) {
                            props.left += "px";
                        }
                        curElem.css( props );
                    }
                }
            };

            jQuery.fn.extend( {

                // offset() relates an element's border box to the document origin
                offset: function( options ) {

                    // Preserve chaining for setter
                    if ( arguments.length ) {
                        return options === undefined ?
                            this :
                            this.each( function( i ) {
                                jQuery.offset.setOffset( this, options, i );
                            } );
                    }

                    var rect, win,
                        elem = this[ 0 ];

                    if ( !elem ) {
                        return;
                    }

                    // Return zeros for disconnected and hidden (display: none) elements (gh-2310)
                    // Support: IE <=11 only
                    // Running getBoundingClientRect on a
                    // disconnected node in IE throws an error
                    if ( !elem.getClientRects().length ) {
                        return { top: 0, left: 0 };
                    }

                    // Get document-relative position by adding viewport scroll to viewport-relative gBCR
                    rect = elem.getBoundingClientRect();
                    win = elem.ownerDocument.defaultView;
                    return {
                        top: rect.top + win.pageYOffset,
                        left: rect.left + win.pageXOffset
                    };
                },

                // position() relates an element's margin box to its offset parent's padding box
                // This corresponds to the behavior of CSS absolute positioning
                position: function() {
                    if ( !this[ 0 ] ) {
                        return;
                    }

                    var offsetParent, offset, doc,
                        elem = this[ 0 ],
                        parentOffset = { top: 0, left: 0 };

                    // position:fixed elements are offset from the viewport, which itself always has zero offset
                    if ( jQuery.css( elem, "position" ) === "fixed" ) {

                        // Assume position:fixed implies availability of getBoundingClientRect
                        offset = elem.getBoundingClientRect();

                    } else {
                        offset = this.offset();

                        // Account for the *real* offset parent, which can be the document or its root element
                        // when a statically positioned element is identified
                        doc = elem.ownerDocument;
                        offsetParent = elem.offsetParent || doc.documentElement;
                        while ( offsetParent &&
                        ( offsetParent === doc.body || offsetParent === doc.documentElement ) &&
                        jQuery.css( offsetParent, "position" ) === "static" ) {

                            offsetParent = offsetParent.parentNode;
                        }
                        if ( offsetParent && offsetParent !== elem && offsetParent.nodeType === 1 ) {

                            // Incorporate borders into its offset, since they are outside its content origin
                            parentOffset = jQuery( offsetParent ).offset();
                            parentOffset.top += jQuery.css( offsetParent, "borderTopWidth", true );
                            parentOffset.left += jQuery.css( offsetParent, "borderLeftWidth", true );
                        }
                    }

                    // Subtract parent offsets and element margins
                    return {
                        top: offset.top - parentOffset.top - jQuery.css( elem, "marginTop", true ),
                        left: offset.left - parentOffset.left - jQuery.css( elem, "marginLeft", true )
                    };
                },

                // This method will return documentElement in the following cases:
                // 1) For the element inside the iframe without offsetParent, this method will return
                //    documentElement of the parent window
                // 2) For the hidden or detached element
                // 3) For body or html element, i.e. in case of the html node - it will return itself
                //
                // but those exceptions were never presented as a real life use-cases
                // and might be considered as more preferable results.
                //
                // This logic, however, is not guaranteed and can change at any point in the future
                offsetParent: function() {
                    return this.map( function() {
                        var offsetParent = this.offsetParent;

                        while ( offsetParent && jQuery.css( offsetParent, "position" ) === "static" ) {
                            offsetParent = offsetParent.offsetParent;
                        }

                        return offsetParent || documentElement;
                    } );
                }
            } );

// Create scrollLeft and scrollTop methods
            jQuery.each( { scrollLeft: "pageXOffset", scrollTop: "pageYOffset" }, function( method, prop ) {
                var top = "pageYOffset" === prop;

                jQuery.fn[ method ] = function( val ) {
                    return access( this, function( elem, method, val ) {

                        // Coalesce documents and windows
                        var win;
                        if ( isWindow( elem ) ) {
                            win = elem;
                        } else if ( elem.nodeType === 9 ) {
                            win = elem.defaultView;
                        }

                        if ( val === undefined ) {
                            return win ? win[ prop ] : elem[ method ];
                        }

                        if ( win ) {
                            win.scrollTo(
                                !top ? val : win.pageXOffset,
                                top ? val : win.pageYOffset
                            );

                        } else {
                            elem[ method ] = val;
                        }
                    }, method, val, arguments.length );
                };
            } );

// Support: Safari <=7 - 9.1, Chrome <=37 - 49
// Add the top/left cssHooks using jQuery.fn.position
// Webkit bug: https://bugs.webkit.org/show_bug.cgi?id=29084
// Blink bug: https://bugs.chromium.org/p/chromium/issues/detail?id=589347
// getComputedStyle returns percent when specified for top/left/bottom/right;
// rather than make the css module depend on the offset module, just check for it here
            jQuery.each( [ "top", "left" ], function( _i, prop ) {
                jQuery.cssHooks[ prop ] = addGetHookIf( support.pixelPosition,
                    function( elem, computed ) {
                        if ( computed ) {
                            computed = curCSS( elem, prop );

                            // If curCSS returns percentage, fallback to offset
                            return rnumnonpx.test( computed ) ?
                                jQuery( elem ).position()[ prop ] + "px" :
                                computed;
                        }
                    }
                );
            } );


// Create innerHeight, innerWidth, height, width, outerHeight and outerWidth methods
            jQuery.each( { Height: "height", Width: "width" }, function( name, type ) {
                jQuery.each( { padding: "inner" + name, content: type, "": "outer" + name },
                    function( defaultExtra, funcName ) {

                        // Margin is only for outerHeight, outerWidth
                        jQuery.fn[ funcName ] = function( margin, value ) {
                            var chainable = arguments.length && ( defaultExtra || typeof margin !== "boolean" ),
                                extra = defaultExtra || ( margin === true || value === true ? "margin" : "border" );

                            return access( this, function( elem, type, value ) {
                                var doc;

                                if ( isWindow( elem ) ) {

                                    // $( window ).outerWidth/Height return w/h including scrollbars (gh-1729)
                                    return funcName.indexOf( "outer" ) === 0 ?
                                        elem[ "inner" + name ] :
                                        elem.document.documentElement[ "client" + name ];
                                }

                                // Get document width or height
                                if ( elem.nodeType === 9 ) {
                                    doc = elem.documentElement;

                                    // Either scroll[Width/Height] or offset[Width/Height] or client[Width/Height],
                                    // whichever is greatest
                                    return Math.max(
                                        elem.body[ "scroll" + name ], doc[ "scroll" + name ],
                                        elem.body[ "offset" + name ], doc[ "offset" + name ],
                                        doc[ "client" + name ]
                                    );
                                }

                                return value === undefined ?

                                    // Get width or height on the element, requesting but not forcing parseFloat
                                    jQuery.css( elem, type, extra ) :

                                    // Set width or height on the element
                                    jQuery.style( elem, type, value, extra );
                            }, type, chainable ? margin : undefined, chainable );
                        };
                    } );
            } );


            jQuery.each( [
                "ajaxStart",
                "ajaxStop",
                "ajaxComplete",
                "ajaxError",
                "ajaxSuccess",
                "ajaxSend"
            ], function( _i, type ) {
                jQuery.fn[ type ] = function( fn ) {
                    return this.on( type, fn );
                };
            } );




            jQuery.fn.extend( {

                bind: function( types, data, fn ) {
                    return this.on( types, null, data, fn );
                },
                unbind: function( types, fn ) {
                    return this.off( types, null, fn );
                },

                delegate: function( selector, types, data, fn ) {
                    return this.on( types, selector, data, fn );
                },
                undelegate: function( selector, types, fn ) {

                    // ( namespace ) or ( selector, types [, fn] )
                    return arguments.length === 1 ?
                        this.off( selector, "**" ) :
                        this.off( types, selector || "**", fn );
                },

                hover: function( fnOver, fnOut ) {
                    return this.mouseenter( fnOver ).mouseleave( fnOut || fnOver );
                }
            } );

            jQuery.each( ( "blur focus focusin focusout resize scroll click dblclick " +
                "mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " +
                "change select submit keydown keypress keyup contextmenu" ).split( " " ),
                function( _i, name ) {

                    // Handle event binding
                    jQuery.fn[ name ] = function( data, fn ) {
                        return arguments.length > 0 ?
                            this.on( name, null, data, fn ) :
                            this.trigger( name );
                    };
                } );




// Support: Android <=4.0 only
// Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;

// Bind a function to a context, optionally partially applying any
// arguments.
// jQuery.proxy is deprecated to promote standards (specifically Function#bind)
// However, it is not slated for removal any time soon
            jQuery.proxy = function( fn, context ) {
                var tmp, args, proxy;

                if ( typeof context === "string" ) {
                    tmp = fn[ context ];
                    context = fn;
                    fn = tmp;
                }

                // Quick check to determine if target is callable, in the spec
                // this throws a TypeError, but we will just return undefined.
                if ( !isFunction( fn ) ) {
                    return undefined;
                }

                // Simulated bind
                args = slice.call( arguments, 2 );
                proxy = function() {
                    return fn.apply( context || this, args.concat( slice.call( arguments ) ) );
                };

                // Set the guid of unique handler to the same of original handler, so it can be removed
                proxy.guid = fn.guid = fn.guid || jQuery.guid++;

                return proxy;
            };

            jQuery.holdReady = function( hold ) {
                if ( hold ) {
                    jQuery.readyWait++;
                } else {
                    jQuery.ready( true );
                }
            };
            jQuery.isArray = Array.isArray;
            jQuery.parseJSON = JSON.parse;
            jQuery.nodeName = nodeName;
            jQuery.isFunction = isFunction;
            jQuery.isWindow = isWindow;
            jQuery.camelCase = camelCase;
            jQuery.type = toType;

            jQuery.now = Date.now;

            jQuery.isNumeric = function( obj ) {

                // As of jQuery 3.0, isNumeric is limited to
                // strings and numbers (primitives or objects)
                // that can be coerced to finite numbers (gh-2662)
                var type = jQuery.type( obj );
                return ( type === "number" || type === "string" ) &&

                    // parseFloat NaNs numeric-cast false positives ("")
                    // ...but misinterprets leading-number strings, particularly hex literals ("0x...")
                    // subtraction forces infinities to NaN
                    !isNaN( obj - parseFloat( obj ) );
            };

            jQuery.trim = function( text ) {
                return text == null ?
                    "" :
                    ( text + "" ).replace( rtrim, "" );
            };



// Register as a named AMD module, since jQuery can be concatenated with other
// files that may use define, but not via a proper concatenation script that
// understands anonymous AMD modules. A named AMD is safest and most robust
// way to register. Lowercase jquery is used because AMD module names are
// derived from file names, and jQuery is normally delivered in a lowercase
// file name. Do this after creating the global so that if an AMD module wants
// to call noConflict to hide this version of jQuery, it will work.

// Note that for maximum portability, libraries that are not jQuery should
// declare themselves as anonymous modules, and avoid setting a global if an
// AMD loader is present. jQuery is a special case. For more information, see
// https://github.com/jrburke/requirejs/wiki/Updating-existing-libraries#wiki-anon

            if ( true ) {
                !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function() {
                    return jQuery;
                }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
                __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
            }




            var

                // Map over jQuery in case of overwrite
                _jQuery = window.jQuery,

                // Map over the $ in case of overwrite
                _$ = window.$;

            jQuery.noConflict = function( deep ) {
                if ( window.$ === jQuery ) {
                    window.$ = _$;
                }

                if ( deep && window.jQuery === jQuery ) {
                    window.jQuery = _jQuery;
                }

                return jQuery;
            };

// Expose jQuery and $ identifiers, even in AMD
// (#7102#comment:10, https://github.com/jquery/jquery/pull/557)
// and CommonJS for browser emulators (#13566)
            if ( typeof noGlobal === "undefined" ) {
                window.jQuery = window.$ = jQuery;
            }




            return jQuery;
        } );


        /***/ }),

    /***/ "./node_modules/laravel-echo/dist/echo.js":
    /*!************************************************!*\
  !*** ./node_modules/laravel-echo/dist/echo.js ***!
  \************************************************/
    /*! exports provided: default */
    /***/ (function(module, __webpack_exports__, __webpack_require__) {

        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* WEBPACK VAR INJECTION */(function(jQuery) {function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

            function _defineProperties(target, props) {
                for (var i = 0; i < props.length; i++) {
                    var descriptor = props[i];
                    descriptor.enumerable = descriptor.enumerable || false;
                    descriptor.configurable = true;
                    if ("value" in descriptor) descriptor.writable = true;
                    Object.defineProperty(target, descriptor.key, descriptor);
                }
            }

            function _createClass(Constructor, protoProps, staticProps) {
                if (protoProps) _defineProperties(Constructor.prototype, protoProps);
                if (staticProps) _defineProperties(Constructor, staticProps);
                return Constructor;
            }

            function _extends() {
                _extends = Object.assign || function (target) {
                    for (var i = 1; i < arguments.length; i++) {
                        var source = arguments[i];

                        for (var key in source) {
                            if (Object.prototype.hasOwnProperty.call(source, key)) {
                                target[key] = source[key];
                            }
                        }
                    }

                    return target;
                };

                return _extends.apply(this, arguments);
            }

            function _inherits(subClass, superClass) {
                if (typeof superClass !== "function" && superClass !== null) {
                    throw new TypeError("Super expression must either be null or a function");
                }

                subClass.prototype = Object.create(superClass && superClass.prototype, {
                    constructor: {
                        value: subClass,
                        writable: true,
                        configurable: true
                    }
                });
                if (superClass) _setPrototypeOf(subClass, superClass);
            }

            function _getPrototypeOf(o) {
                _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
                    return o.__proto__ || Object.getPrototypeOf(o);
                };
                return _getPrototypeOf(o);
            }

            function _setPrototypeOf(o, p) {
                _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
                    o.__proto__ = p;
                    return o;
                };

                return _setPrototypeOf(o, p);
            }

            function _isNativeReflectConstruct() {
                if (typeof Reflect === "undefined" || !Reflect.construct) return false;
                if (Reflect.construct.sham) return false;
                if (typeof Proxy === "function") return true;

                try {
                    Date.prototype.toString.call(Reflect.construct(Date, [], function () {}));
                    return true;
                } catch (e) {
                    return false;
                }
            }

            function _assertThisInitialized(self) {
                if (self === void 0) {
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                }

                return self;
            }

            function _possibleConstructorReturn(self, call) {
                if (call && (typeof call === "object" || typeof call === "function")) {
                    return call;
                }

                return _assertThisInitialized(self);
            }

            function _createSuper(Derived) {
                var hasNativeReflectConstruct = _isNativeReflectConstruct();

                return function () {
                    var Super = _getPrototypeOf(Derived),
                        result;

                    if (hasNativeReflectConstruct) {
                        var NewTarget = _getPrototypeOf(this).constructor;

                        result = Reflect.construct(Super, arguments, NewTarget);
                    } else {
                        result = Super.apply(this, arguments);
                    }

                    return _possibleConstructorReturn(this, result);
                };
            }

            var Connector = /*#__PURE__*/function () {
                /**
                 * Create a new class instance.
                 */
                function Connector(options) {
                    _classCallCheck(this, Connector);

                    /**
                     * Default connector options.
                     */
                    this._defaultOptions = {
                        auth: {
                            headers: {}
                        },
                        authEndpoint: '/broadcasting/auth',
                        broadcaster: 'pusher',
                        csrfToken: null,
                        host: null,
                        key: null,
                        namespace: 'App.Events'
                    };
                    this.setOptions(options);
                    this.connect();
                }
                /**
                 * Merge the custom options with the defaults.
                 */


                _createClass(Connector, [{
                    key: "setOptions",
                    value: function setOptions(options) {
                        this.options = _extends(this._defaultOptions, options);

                        if (this.csrfToken()) {
                            this.options.auth.headers['X-CSRF-TOKEN'] = this.csrfToken();
                        }

                        return options;
                    }
                    /**
                     * Extract the CSRF token from the page.
                     */

                }, {
                    key: "csrfToken",
                    value: function csrfToken() {
                        var selector;

                        if (typeof window !== 'undefined' && window['Laravel'] && window['Laravel'].csrfToken) {
                            return window['Laravel'].csrfToken;
                        } else if (this.options.csrfToken) {
                            return this.options.csrfToken;
                        } else if (typeof document !== 'undefined' && typeof document.querySelector === 'function' && (selector = document.querySelector('meta[name="csrf-token"]'))) {
                            return selector.getAttribute('content');
                        }

                        return null;
                    }
                }]);

                return Connector;
            }();

            /**
             * This class represents a basic channel.
             */
            var Channel = /*#__PURE__*/function () {
                function Channel() {
                    _classCallCheck(this, Channel);
                }

                _createClass(Channel, [{
                    key: "listenForWhisper",

                    /**
                     * Listen for a whisper event on the channel instance.
                     */
                    value: function listenForWhisper(event, callback) {
                        return this.listen('.client-' + event, callback);
                    }
                    /**
                     * Listen for an event on the channel instance.
                     */

                }, {
                    key: "notification",
                    value: function notification(callback) {
                        return this.listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', callback);
                    }
                    /**
                     * Stop listening for a whisper event on the channel instance.
                     */

                }, {
                    key: "stopListeningForWhisper",
                    value: function stopListeningForWhisper(event, callback) {
                        return this.stopListening('.client-' + event, callback);
                    }
                }]);

                return Channel;
            }();

            /**
             * Event name formatter
             */
            var EventFormatter = /*#__PURE__*/function () {
                /**
                 * Create a new class instance.
                 */
                function EventFormatter(namespace) {
                    _classCallCheck(this, EventFormatter);

                    this.setNamespace(namespace);
                }
                /**
                 * Format the given event name.
                 */


                _createClass(EventFormatter, [{
                    key: "format",
                    value: function format(event) {
                        if (event.charAt(0) === '.' || event.charAt(0) === '\\') {
                            return event.substr(1);
                        } else if (this.namespace) {
                            event = this.namespace + '.' + event;
                        }

                        return event.replace(/\./g, '\\');
                    }
                    /**
                     * Set the event namespace.
                     */

                }, {
                    key: "setNamespace",
                    value: function setNamespace(value) {
                        this.namespace = value;
                    }
                }]);

                return EventFormatter;
            }();

            /**
             * This class represents a Pusher channel.
             */

            var PusherChannel = /*#__PURE__*/function (_Channel) {
                _inherits(PusherChannel, _Channel);

                var _super = _createSuper(PusherChannel);

                /**
                 * Create a new class instance.
                 */
                function PusherChannel(pusher, name, options) {
                    var _this;

                    _classCallCheck(this, PusherChannel);

                    _this = _super.call(this);
                    _this.name = name;
                    _this.pusher = pusher;
                    _this.options = options;
                    _this.eventFormatter = new EventFormatter(_this.options.namespace);

                    _this.subscribe();

                    return _this;
                }
                /**
                 * Subscribe to a Pusher channel.
                 */


                _createClass(PusherChannel, [{
                    key: "subscribe",
                    value: function subscribe() {
                        this.subscription = this.pusher.subscribe(this.name);
                    }
                    /**
                     * Unsubscribe from a Pusher channel.
                     */

                }, {
                    key: "unsubscribe",
                    value: function unsubscribe() {
                        this.pusher.unsubscribe(this.name);
                    }
                    /**
                     * Listen for an event on the channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(event, callback) {
                        this.on(this.eventFormatter.format(event), callback);
                        return this;
                    }
                    /**
                     * Stop listening for an event on the channel instance.
                     */

                }, {
                    key: "stopListening",
                    value: function stopListening(event, callback) {
                        if (callback) {
                            this.subscription.unbind(this.eventFormatter.format(event), callback);
                        } else {
                            this.subscription.unbind(this.eventFormatter.format(event));
                        }

                        return this;
                    }
                    /**
                     * Register a callback to be called anytime a subscription succeeds.
                     */

                }, {
                    key: "subscribed",
                    value: function subscribed(callback) {
                        this.on('pusher:subscription_succeeded', function () {
                            callback();
                        });
                        return this;
                    }
                    /**
                     * Register a callback to be called anytime a subscription error occurs.
                     */

                }, {
                    key: "error",
                    value: function error(callback) {
                        this.on('pusher:subscription_error', function (status) {
                            callback(status);
                        });
                        return this;
                    }
                    /**
                     * Bind a channel to an event.
                     */

                }, {
                    key: "on",
                    value: function on(event, callback) {
                        this.subscription.bind(event, callback);
                        return this;
                    }
                }]);

                return PusherChannel;
            }(Channel);

            /**
             * This class represents a Pusher private channel.
             */

            var PusherPrivateChannel = /*#__PURE__*/function (_PusherChannel) {
                _inherits(PusherPrivateChannel, _PusherChannel);

                var _super = _createSuper(PusherPrivateChannel);

                function PusherPrivateChannel() {
                    _classCallCheck(this, PusherPrivateChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(PusherPrivateChannel, [{
                    key: "whisper",

                    /**
                     * Trigger client event on the channel.
                     */
                    value: function whisper(eventName, data) {
                        this.pusher.channels.channels[this.name].trigger("client-".concat(eventName), data);
                        return this;
                    }
                }]);

                return PusherPrivateChannel;
            }(PusherChannel);

            /**
             * This class represents a Pusher private channel.
             */

            var PusherEncryptedPrivateChannel = /*#__PURE__*/function (_PusherChannel) {
                _inherits(PusherEncryptedPrivateChannel, _PusherChannel);

                var _super = _createSuper(PusherEncryptedPrivateChannel);

                function PusherEncryptedPrivateChannel() {
                    _classCallCheck(this, PusherEncryptedPrivateChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(PusherEncryptedPrivateChannel, [{
                    key: "whisper",

                    /**
                     * Trigger client event on the channel.
                     */
                    value: function whisper(eventName, data) {
                        this.pusher.channels.channels[this.name].trigger("client-".concat(eventName), data);
                        return this;
                    }
                }]);

                return PusherEncryptedPrivateChannel;
            }(PusherChannel);

            /**
             * This class represents a Pusher presence channel.
             */

            var PusherPresenceChannel = /*#__PURE__*/function (_PusherChannel) {
                _inherits(PusherPresenceChannel, _PusherChannel);

                var _super = _createSuper(PusherPresenceChannel);

                function PusherPresenceChannel() {
                    _classCallCheck(this, PusherPresenceChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(PusherPresenceChannel, [{
                    key: "here",

                    /**
                     * Register a callback to be called anytime the member list changes.
                     */
                    value: function here(callback) {
                        this.on('pusher:subscription_succeeded', function (data) {
                            callback(Object.keys(data.members).map(function (k) {
                                return data.members[k];
                            }));
                        });
                        return this;
                    }
                    /**
                     * Listen for someone joining the channel.
                     */

                }, {
                    key: "joining",
                    value: function joining(callback) {
                        this.on('pusher:member_added', function (member) {
                            callback(member.info);
                        });
                        return this;
                    }
                    /**
                     * Listen for someone leaving the channel.
                     */

                }, {
                    key: "leaving",
                    value: function leaving(callback) {
                        this.on('pusher:member_removed', function (member) {
                            callback(member.info);
                        });
                        return this;
                    }
                    /**
                     * Trigger client event on the channel.
                     */

                }, {
                    key: "whisper",
                    value: function whisper(eventName, data) {
                        this.pusher.channels.channels[this.name].trigger("client-".concat(eventName), data);
                        return this;
                    }
                }]);

                return PusherPresenceChannel;
            }(PusherChannel);

            /**
             * This class represents a Socket.io channel.
             */

            var SocketIoChannel = /*#__PURE__*/function (_Channel) {
                _inherits(SocketIoChannel, _Channel);

                var _super = _createSuper(SocketIoChannel);

                /**
                 * Create a new class instance.
                 */
                function SocketIoChannel(socket, name, options) {
                    var _this;

                    _classCallCheck(this, SocketIoChannel);

                    _this = _super.call(this);
                    /**
                     * The event callbacks applied to the socket.
                     */

                    _this.events = {};
                    /**
                     * User supplied callbacks for events on this channel.
                     */

                    _this.listeners = {};
                    _this.name = name;
                    _this.socket = socket;
                    _this.options = options;
                    _this.eventFormatter = new EventFormatter(_this.options.namespace);

                    _this.subscribe();

                    return _this;
                }
                /**
                 * Subscribe to a Socket.io channel.
                 */


                _createClass(SocketIoChannel, [{
                    key: "subscribe",
                    value: function subscribe() {
                        this.socket.emit('subscribe', {
                            channel: this.name,
                            auth: this.options.auth || {}
                        });
                    }
                    /**
                     * Unsubscribe from channel and ubind event callbacks.
                     */

                }, {
                    key: "unsubscribe",
                    value: function unsubscribe() {
                        this.unbind();
                        this.socket.emit('unsubscribe', {
                            channel: this.name,
                            auth: this.options.auth || {}
                        });
                    }
                    /**
                     * Listen for an event on the channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(event, callback) {
                        this.on(this.eventFormatter.format(event), callback);
                        return this;
                    }
                    /**
                     * Stop listening for an event on the channel instance.
                     */

                }, {
                    key: "stopListening",
                    value: function stopListening(event, callback) {
                        this.unbindEvent(this.eventFormatter.format(event), callback);
                        return this;
                    }
                    /**
                     * Register a callback to be called anytime a subscription succeeds.
                     */

                }, {
                    key: "subscribed",
                    value: function subscribed(callback) {
                        this.on('connect', function (socket) {
                            callback(socket);
                        });
                        return this;
                    }
                    /**
                     * Register a callback to be called anytime an error occurs.
                     */

                }, {
                    key: "error",
                    value: function error(callback) {
                        return this;
                    }
                    /**
                     * Bind the channel's socket to an event and store the callback.
                     */

                }, {
                    key: "on",
                    value: function on(event, callback) {
                        var _this2 = this;

                        this.listeners[event] = this.listeners[event] || [];

                        if (!this.events[event]) {
                            this.events[event] = function (channel, data) {
                                if (_this2.name === channel && _this2.listeners[event]) {
                                    _this2.listeners[event].forEach(function (cb) {
                                        return cb(data);
                                    });
                                }
                            };

                            this.socket.on(event, this.events[event]);
                        }

                        this.listeners[event].push(callback);
                        return this;
                    }
                    /**
                     * Unbind the channel's socket from all stored event callbacks.
                     */

                }, {
                    key: "unbind",
                    value: function unbind() {
                        var _this3 = this;

                        Object.keys(this.events).forEach(function (event) {
                            _this3.unbindEvent(event);
                        });
                    }
                    /**
                     * Unbind the listeners for the given event.
                     */

                }, {
                    key: "unbindEvent",
                    value: function unbindEvent(event, callback) {
                        this.listeners[event] = this.listeners[event] || [];

                        if (callback) {
                            this.listeners[event] = this.listeners[event].filter(function (cb) {
                                return cb !== callback;
                            });
                        }

                        if (!callback || this.listeners[event].length === 0) {
                            if (this.events[event]) {
                                this.socket.removeListener(event, this.events[event]);
                                delete this.events[event];
                            }

                            delete this.listeners[event];
                        }
                    }
                }]);

                return SocketIoChannel;
            }(Channel);

            /**
             * This class represents a Socket.io private channel.
             */

            var SocketIoPrivateChannel = /*#__PURE__*/function (_SocketIoChannel) {
                _inherits(SocketIoPrivateChannel, _SocketIoChannel);

                var _super = _createSuper(SocketIoPrivateChannel);

                function SocketIoPrivateChannel() {
                    _classCallCheck(this, SocketIoPrivateChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(SocketIoPrivateChannel, [{
                    key: "whisper",

                    /**
                     * Trigger client event on the channel.
                     */
                    value: function whisper(eventName, data) {
                        this.socket.emit('client event', {
                            channel: this.name,
                            event: "client-".concat(eventName),
                            data: data
                        });
                        return this;
                    }
                }]);

                return SocketIoPrivateChannel;
            }(SocketIoChannel);

            /**
             * This class represents a Socket.io presence channel.
             */

            var SocketIoPresenceChannel = /*#__PURE__*/function (_SocketIoPrivateChann) {
                _inherits(SocketIoPresenceChannel, _SocketIoPrivateChann);

                var _super = _createSuper(SocketIoPresenceChannel);

                function SocketIoPresenceChannel() {
                    _classCallCheck(this, SocketIoPresenceChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(SocketIoPresenceChannel, [{
                    key: "here",

                    /**
                     * Register a callback to be called anytime the member list changes.
                     */
                    value: function here(callback) {
                        this.on('presence:subscribed', function (members) {
                            callback(members.map(function (m) {
                                return m.user_info;
                            }));
                        });
                        return this;
                    }
                    /**
                     * Listen for someone joining the channel.
                     */

                }, {
                    key: "joining",
                    value: function joining(callback) {
                        this.on('presence:joining', function (member) {
                            return callback(member.user_info);
                        });
                        return this;
                    }
                    /**
                     * Listen for someone leaving the channel.
                     */

                }, {
                    key: "leaving",
                    value: function leaving(callback) {
                        this.on('presence:leaving', function (member) {
                            return callback(member.user_info);
                        });
                        return this;
                    }
                }]);

                return SocketIoPresenceChannel;
            }(SocketIoPrivateChannel);

            /**
             * This class represents a null channel.
             */

            var NullChannel = /*#__PURE__*/function (_Channel) {
                _inherits(NullChannel, _Channel);

                var _super = _createSuper(NullChannel);

                function NullChannel() {
                    _classCallCheck(this, NullChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(NullChannel, [{
                    key: "subscribe",

                    /**
                     * Subscribe to a channel.
                     */
                    value: function subscribe() {} //

                    /**
                     * Unsubscribe from a channel.
                     */

                }, {
                    key: "unsubscribe",
                    value: function unsubscribe() {} //

                    /**
                     * Listen for an event on the channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(event, callback) {
                        return this;
                    }
                    /**
                     * Stop listening for an event on the channel instance.
                     */

                }, {
                    key: "stopListening",
                    value: function stopListening(event, callback) {
                        return this;
                    }
                    /**
                     * Register a callback to be called anytime a subscription succeeds.
                     */

                }, {
                    key: "subscribed",
                    value: function subscribed(callback) {
                        return this;
                    }
                    /**
                     * Register a callback to be called anytime an error occurs.
                     */

                }, {
                    key: "error",
                    value: function error(callback) {
                        return this;
                    }
                    /**
                     * Bind a channel to an event.
                     */

                }, {
                    key: "on",
                    value: function on(event, callback) {
                        return this;
                    }
                }]);

                return NullChannel;
            }(Channel);

            /**
             * This class represents a null private channel.
             */

            var NullPrivateChannel = /*#__PURE__*/function (_NullChannel) {
                _inherits(NullPrivateChannel, _NullChannel);

                var _super = _createSuper(NullPrivateChannel);

                function NullPrivateChannel() {
                    _classCallCheck(this, NullPrivateChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(NullPrivateChannel, [{
                    key: "whisper",

                    /**
                     * Trigger client event on the channel.
                     */
                    value: function whisper(eventName, data) {
                        return this;
                    }
                }]);

                return NullPrivateChannel;
            }(NullChannel);

            /**
             * This class represents a null presence channel.
             */

            var NullPresenceChannel = /*#__PURE__*/function (_NullChannel) {
                _inherits(NullPresenceChannel, _NullChannel);

                var _super = _createSuper(NullPresenceChannel);

                function NullPresenceChannel() {
                    _classCallCheck(this, NullPresenceChannel);

                    return _super.apply(this, arguments);
                }

                _createClass(NullPresenceChannel, [{
                    key: "here",

                    /**
                     * Register a callback to be called anytime the member list changes.
                     */
                    value: function here(callback) {
                        return this;
                    }
                    /**
                     * Listen for someone joining the channel.
                     */

                }, {
                    key: "joining",
                    value: function joining(callback) {
                        return this;
                    }
                    /**
                     * Listen for someone leaving the channel.
                     */

                }, {
                    key: "leaving",
                    value: function leaving(callback) {
                        return this;
                    }
                    /**
                     * Trigger client event on the channel.
                     */

                }, {
                    key: "whisper",
                    value: function whisper(eventName, data) {
                        return this;
                    }
                }]);

                return NullPresenceChannel;
            }(NullChannel);

            /**
             * This class creates a connector to Pusher.
             */

            var PusherConnector = /*#__PURE__*/function (_Connector) {
                _inherits(PusherConnector, _Connector);

                var _super = _createSuper(PusherConnector);

                function PusherConnector() {
                    var _this;

                    _classCallCheck(this, PusherConnector);

                    _this = _super.apply(this, arguments);
                    /**
                     * All of the subscribed channel names.
                     */

                    _this.channels = {};
                    return _this;
                }
                /**
                 * Create a fresh Pusher connection.
                 */


                _createClass(PusherConnector, [{
                    key: "connect",
                    value: function connect() {
                        if (typeof this.options.client !== 'undefined') {
                            this.pusher = this.options.client;
                        } else {
                            this.pusher = new Pusher(this.options.key, this.options);
                        }
                    }
                    /**
                     * Listen for an event on a channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(name, event, callback) {
                        return this.channel(name).listen(event, callback);
                    }
                    /**
                     * Get a channel instance by name.
                     */

                }, {
                    key: "channel",
                    value: function channel(name) {
                        if (!this.channels[name]) {
                            this.channels[name] = new PusherChannel(this.pusher, name, this.options);
                        }

                        return this.channels[name];
                    }
                    /**
                     * Get a private channel instance by name.
                     */

                }, {
                    key: "privateChannel",
                    value: function privateChannel(name) {
                        if (!this.channels['private-' + name]) {
                            this.channels['private-' + name] = new PusherPrivateChannel(this.pusher, 'private-' + name, this.options);
                        }

                        return this.channels['private-' + name];
                    }
                    /**
                     * Get a private encrypted channel instance by name.
                     */

                }, {
                    key: "encryptedPrivateChannel",
                    value: function encryptedPrivateChannel(name) {
                        if (!this.channels['private-encrypted-' + name]) {
                            this.channels['private-encrypted-' + name] = new PusherEncryptedPrivateChannel(this.pusher, 'private-encrypted-' + name, this.options);
                        }

                        return this.channels['private-encrypted-' + name];
                    }
                    /**
                     * Get a presence channel instance by name.
                     */

                }, {
                    key: "presenceChannel",
                    value: function presenceChannel(name) {
                        if (!this.channels['presence-' + name]) {
                            this.channels['presence-' + name] = new PusherPresenceChannel(this.pusher, 'presence-' + name, this.options);
                        }

                        return this.channels['presence-' + name];
                    }
                    /**
                     * Leave the given channel, as well as its private and presence variants.
                     */

                }, {
                    key: "leave",
                    value: function leave(name) {
                        var _this2 = this;

                        var channels = [name, 'private-' + name, 'presence-' + name];
                        channels.forEach(function (name, index) {
                            _this2.leaveChannel(name);
                        });
                    }
                    /**
                     * Leave the given channel.
                     */

                }, {
                    key: "leaveChannel",
                    value: function leaveChannel(name) {
                        if (this.channels[name]) {
                            this.channels[name].unsubscribe();
                            delete this.channels[name];
                        }
                    }
                    /**
                     * Get the socket ID for the connection.
                     */

                }, {
                    key: "socketId",
                    value: function socketId() {
                        return this.pusher.connection.socket_id;
                    }
                    /**
                     * Disconnect Pusher connection.
                     */

                }, {
                    key: "disconnect",
                    value: function disconnect() {
                        this.pusher.disconnect();
                    }
                }]);

                return PusherConnector;
            }(Connector);

            /**
             * This class creates a connnector to a Socket.io server.
             */

            var SocketIoConnector = /*#__PURE__*/function (_Connector) {
                _inherits(SocketIoConnector, _Connector);

                var _super = _createSuper(SocketIoConnector);

                function SocketIoConnector() {
                    var _this;

                    _classCallCheck(this, SocketIoConnector);

                    _this = _super.apply(this, arguments);
                    /**
                     * All of the subscribed channel names.
                     */

                    _this.channels = {};
                    return _this;
                }
                /**
                 * Create a fresh Socket.io connection.
                 */


                _createClass(SocketIoConnector, [{
                    key: "connect",
                    value: function connect() {
                        var _this2 = this;

                        var io = this.getSocketIO();
                        this.socket = io(this.options.host, this.options);
                        this.socket.on('reconnect', function () {
                            Object.values(_this2.channels).forEach(function (channel) {
                                channel.subscribe();
                            });
                        });
                        return this.socket;
                    }
                    /**
                     * Get socket.io module from global scope or options.
                     */

                }, {
                    key: "getSocketIO",
                    value: function getSocketIO() {
                        if (typeof this.options.client !== 'undefined') {
                            return this.options.client;
                        }

                        if (typeof io !== 'undefined') {
                            return io;
                        }

                        throw new Error('Socket.io client not found. Should be globally available or passed via options.client');
                    }
                    /**
                     * Listen for an event on a channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(name, event, callback) {
                        return this.channel(name).listen(event, callback);
                    }
                    /**
                     * Get a channel instance by name.
                     */

                }, {
                    key: "channel",
                    value: function channel(name) {
                        if (!this.channels[name]) {
                            this.channels[name] = new SocketIoChannel(this.socket, name, this.options);
                        }

                        return this.channels[name];
                    }
                    /**
                     * Get a private channel instance by name.
                     */

                }, {
                    key: "privateChannel",
                    value: function privateChannel(name) {
                        if (!this.channels['private-' + name]) {
                            this.channels['private-' + name] = new SocketIoPrivateChannel(this.socket, 'private-' + name, this.options);
                        }

                        return this.channels['private-' + name];
                    }
                    /**
                     * Get a presence channel instance by name.
                     */

                }, {
                    key: "presenceChannel",
                    value: function presenceChannel(name) {
                        if (!this.channels['presence-' + name]) {
                            this.channels['presence-' + name] = new SocketIoPresenceChannel(this.socket, 'presence-' + name, this.options);
                        }

                        return this.channels['presence-' + name];
                    }
                    /**
                     * Leave the given channel, as well as its private and presence variants.
                     */

                }, {
                    key: "leave",
                    value: function leave(name) {
                        var _this3 = this;

                        var channels = [name, 'private-' + name, 'presence-' + name];
                        channels.forEach(function (name) {
                            _this3.leaveChannel(name);
                        });
                    }
                    /**
                     * Leave the given channel.
                     */

                }, {
                    key: "leaveChannel",
                    value: function leaveChannel(name) {
                        if (this.channels[name]) {
                            this.channels[name].unsubscribe();
                            delete this.channels[name];
                        }
                    }
                    /**
                     * Get the socket ID for the connection.
                     */

                }, {
                    key: "socketId",
                    value: function socketId() {
                        return this.socket.id;
                    }
                    /**
                     * Disconnect Socketio connection.
                     */

                }, {
                    key: "disconnect",
                    value: function disconnect() {
                        this.socket.disconnect();
                    }
                }]);

                return SocketIoConnector;
            }(Connector);

            /**
             * This class creates a null connector.
             */

            var NullConnector = /*#__PURE__*/function (_Connector) {
                _inherits(NullConnector, _Connector);

                var _super = _createSuper(NullConnector);

                function NullConnector() {
                    var _this;

                    _classCallCheck(this, NullConnector);

                    _this = _super.apply(this, arguments);
                    /**
                     * All of the subscribed channel names.
                     */

                    _this.channels = {};
                    return _this;
                }
                /**
                 * Create a fresh connection.
                 */


                _createClass(NullConnector, [{
                    key: "connect",
                    value: function connect() {} //

                    /**
                     * Listen for an event on a channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(name, event, callback) {
                        return new NullChannel();
                    }
                    /**
                     * Get a channel instance by name.
                     */

                }, {
                    key: "channel",
                    value: function channel(name) {
                        return new NullChannel();
                    }
                    /**
                     * Get a private channel instance by name.
                     */

                }, {
                    key: "privateChannel",
                    value: function privateChannel(name) {
                        return new NullPrivateChannel();
                    }
                    /**
                     * Get a presence channel instance by name.
                     */

                }, {
                    key: "presenceChannel",
                    value: function presenceChannel(name) {
                        return new NullPresenceChannel();
                    }
                    /**
                     * Leave the given channel, as well as its private and presence variants.
                     */

                }, {
                    key: "leave",
                    value: function leave(name) {} //

                    /**
                     * Leave the given channel.
                     */

                }, {
                    key: "leaveChannel",
                    value: function leaveChannel(name) {} //

                    /**
                     * Get the socket ID for the connection.
                     */

                }, {
                    key: "socketId",
                    value: function socketId() {
                        return 'fake-socket-id';
                    }
                    /**
                     * Disconnect the connection.
                     */

                }, {
                    key: "disconnect",
                    value: function disconnect() {//
                    }
                }]);

                return NullConnector;
            }(Connector);

            /**
             * This class is the primary API for interacting with broadcasting.
             */

            var Echo = /*#__PURE__*/function () {
                /**
                 * Create a new class instance.
                 */
                function Echo(options) {
                    _classCallCheck(this, Echo);

                    this.options = options;
                    this.connect();

                    if (!this.options.withoutInterceptors) {
                        this.registerInterceptors();
                    }
                }
                /**
                 * Get a channel instance by name.
                 */


                _createClass(Echo, [{
                    key: "channel",
                    value: function channel(_channel) {
                        return this.connector.channel(_channel);
                    }
                    /**
                     * Create a new connection.
                     */

                }, {
                    key: "connect",
                    value: function connect() {
                        if (this.options.broadcaster == 'pusher') {
                            this.connector = new PusherConnector(this.options);
                        } else if (this.options.broadcaster == 'socket.io') {
                            this.connector = new SocketIoConnector(this.options);
                        } else if (this.options.broadcaster == 'null') {
                            this.connector = new NullConnector(this.options);
                        } else if (typeof this.options.broadcaster == 'function') {
                            this.connector = new this.options.broadcaster(this.options);
                        }
                    }
                    /**
                     * Disconnect from the Echo server.
                     */

                }, {
                    key: "disconnect",
                    value: function disconnect() {
                        this.connector.disconnect();
                    }
                    /**
                     * Get a presence channel instance by name.
                     */

                }, {
                    key: "join",
                    value: function join(channel) {
                        return this.connector.presenceChannel(channel);
                    }
                    /**
                     * Leave the given channel, as well as its private and presence variants.
                     */

                }, {
                    key: "leave",
                    value: function leave(channel) {
                        this.connector.leave(channel);
                    }
                    /**
                     * Leave the given channel.
                     */

                }, {
                    key: "leaveChannel",
                    value: function leaveChannel(channel) {
                        this.connector.leaveChannel(channel);
                    }
                    /**
                     * Listen for an event on a channel instance.
                     */

                }, {
                    key: "listen",
                    value: function listen(channel, event, callback) {
                        return this.connector.listen(channel, event, callback);
                    }
                    /**
                     * Get a private channel instance by name.
                     */

                }, {
                    key: "private",
                    value: function _private(channel) {
                        return this.connector.privateChannel(channel);
                    }
                    /**
                     * Get a private encrypted channel instance by name.
                     */

                }, {
                    key: "encryptedPrivate",
                    value: function encryptedPrivate(channel) {
                        return this.connector.encryptedPrivateChannel(channel);
                    }
                    /**
                     * Get the Socket ID for the connection.
                     */

                }, {
                    key: "socketId",
                    value: function socketId() {
                        return this.connector.socketId();
                    }
                    /**
                     * Register 3rd party request interceptiors. These are used to automatically
                     * send a connections socket id to a Laravel app with a X-Socket-Id header.
                     */

                }, {
                    key: "registerInterceptors",
                    value: function registerInterceptors() {
                        if (typeof Vue === 'function' && Vue.http) {
                            this.registerVueRequestInterceptor();
                        }

                        if (typeof axios === 'function') {
                            this.registerAxiosRequestInterceptor();
                        }

                        if (typeof jQuery === 'function') {
                            this.registerjQueryAjaxSetup();
                        }
                    }
                    /**
                     * Register a Vue HTTP interceptor to add the X-Socket-ID header.
                     */

                }, {
                    key: "registerVueRequestInterceptor",
                    value: function registerVueRequestInterceptor() {
                        var _this = this;

                        Vue.http.interceptors.push(function (request, next) {
                            if (_this.socketId()) {
                                request.headers.set('X-Socket-ID', _this.socketId());
                            }

                            next();
                        });
                    }
                    /**
                     * Register an Axios HTTP interceptor to add the X-Socket-ID header.
                     */

                }, {
                    key: "registerAxiosRequestInterceptor",
                    value: function registerAxiosRequestInterceptor() {
                        var _this2 = this;

                        axios.interceptors.request.use(function (config) {
                            if (_this2.socketId()) {
                                config.headers['X-Socket-Id'] = _this2.socketId();
                            }

                            return config;
                        });
                    }
                    /**
                     * Register jQuery AjaxPrefilter to add the X-Socket-ID header.
                     */

                }, {
                    key: "registerjQueryAjaxSetup",
                    value: function registerjQueryAjaxSetup() {
                        var _this3 = this;

                        if (typeof jQuery.ajax != 'undefined') {
                            jQuery.ajaxPrefilter(function (options, originalOptions, xhr) {
                                if (_this3.socketId()) {
                                    xhr.setRequestHeader('X-Socket-Id', _this3.socketId());
                                }
                            });
                        }
                    }
                }]);

                return Echo;
            }();

            /* harmony default export */ __webpack_exports__["default"] = (Echo);

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

        /***/ }),

    /***/ "./node_modules/ms/index.js":
    /*!**********************************!*\
  !*** ./node_modules/ms/index.js ***!
  \**********************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Helpers.
         */

        var s = 1000;
        var m = s * 60;
        var h = m * 60;
        var d = h * 24;
        var y = d * 365.25;

        /**
         * Parse or format the given `val`.
         *
         * Options:
         *
         *  - `long` verbose formatting [false]
         *
         * @param {String|Number} val
         * @param {Object} [options]
         * @throws {Error} throw an error if val is not a non-empty string or a number
         * @return {String|Number}
         * @api public
         */

        module.exports = function(val, options) {
            options = options || {};
            var type = typeof val;
            if (type === 'string' && val.length > 0) {
                return parse(val);
            } else if (type === 'number' && isNaN(val) === false) {
                return options.long ? fmtLong(val) : fmtShort(val);
            }
            throw new Error(
                'val is not a non-empty string or a valid number. val=' +
                JSON.stringify(val)
            );
        };

        /**
         * Parse the given `str` and return milliseconds.
         *
         * @param {String} str
         * @return {Number}
         * @api private
         */

        function parse(str) {
            str = String(str);
            if (str.length > 100) {
                return;
            }
            var match = /^((?:\d+)?\.?\d+) *(milliseconds?|msecs?|ms|seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|years?|yrs?|y)?$/i.exec(
                str
            );
            if (!match) {
                return;
            }
            var n = parseFloat(match[1]);
            var type = (match[2] || 'ms').toLowerCase();
            switch (type) {
                case 'years':
                case 'year':
                case 'yrs':
                case 'yr':
                case 'y':
                    return n * y;
                case 'days':
                case 'day':
                case 'd':
                    return n * d;
                case 'hours':
                case 'hour':
                case 'hrs':
                case 'hr':
                case 'h':
                    return n * h;
                case 'minutes':
                case 'minute':
                case 'mins':
                case 'min':
                case 'm':
                    return n * m;
                case 'seconds':
                case 'second':
                case 'secs':
                case 'sec':
                case 's':
                    return n * s;
                case 'milliseconds':
                case 'millisecond':
                case 'msecs':
                case 'msec':
                case 'ms':
                    return n;
                default:
                    return undefined;
            }
        }

        /**
         * Short format for `ms`.
         *
         * @param {Number} ms
         * @return {String}
         * @api private
         */

        function fmtShort(ms) {
            if (ms >= d) {
                return Math.round(ms / d) + 'd';
            }
            if (ms >= h) {
                return Math.round(ms / h) + 'h';
            }
            if (ms >= m) {
                return Math.round(ms / m) + 'm';
            }
            if (ms >= s) {
                return Math.round(ms / s) + 's';
            }
            return ms + 'ms';
        }

        /**
         * Long format for `ms`.
         *
         * @param {Number} ms
         * @return {String}
         * @api private
         */

        function fmtLong(ms) {
            return plural(ms, d, 'day') ||
                plural(ms, h, 'hour') ||
                plural(ms, m, 'minute') ||
                plural(ms, s, 'second') ||
                ms + ' ms';
        }

        /**
         * Pluralization helper.
         */

        function plural(ms, n, name) {
            if (ms < n) {
                return;
            }
            if (ms < n * 1.5) {
                return Math.floor(ms / n) + ' ' + name;
            }
            return Math.ceil(ms / n) + ' ' + name + 's';
        }


        /***/ }),

    /***/ "./node_modules/process/browser.js":
    /*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

// shim for using process in browser
        var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

        var cachedSetTimeout;
        var cachedClearTimeout;

        function defaultSetTimout() {
            throw new Error('setTimeout has not been defined');
        }
        function defaultClearTimeout () {
            throw new Error('clearTimeout has not been defined');
        }
        (function () {
            try {
                if (typeof setTimeout === 'function') {
                    cachedSetTimeout = setTimeout;
                } else {
                    cachedSetTimeout = defaultSetTimout;
                }
            } catch (e) {
                cachedSetTimeout = defaultSetTimout;
            }
            try {
                if (typeof clearTimeout === 'function') {
                    cachedClearTimeout = clearTimeout;
                } else {
                    cachedClearTimeout = defaultClearTimeout;
                }
            } catch (e) {
                cachedClearTimeout = defaultClearTimeout;
            }
        } ())
        function runTimeout(fun) {
            if (cachedSetTimeout === setTimeout) {
                //normal enviroments in sane situations
                return setTimeout(fun, 0);
            }
            // if setTimeout wasn't available but was latter defined
            if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
                cachedSetTimeout = setTimeout;
                return setTimeout(fun, 0);
            }
            try {
                // when when somebody has screwed with setTimeout but no I.E. maddness
                return cachedSetTimeout(fun, 0);
            } catch(e){
                try {
                    // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
                    return cachedSetTimeout.call(null, fun, 0);
                } catch(e){
                    // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
                    return cachedSetTimeout.call(this, fun, 0);
                }
            }


        }
        function runClearTimeout(marker) {
            if (cachedClearTimeout === clearTimeout) {
                //normal enviroments in sane situations
                return clearTimeout(marker);
            }
            // if clearTimeout wasn't available but was latter defined
            if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
                cachedClearTimeout = clearTimeout;
                return clearTimeout(marker);
            }
            try {
                // when when somebody has screwed with setTimeout but no I.E. maddness
                return cachedClearTimeout(marker);
            } catch (e){
                try {
                    // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
                    return cachedClearTimeout.call(null, marker);
                } catch (e){
                    // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
                    // Some versions of I.E. have different rules for clearTimeout vs setTimeout
                    return cachedClearTimeout.call(this, marker);
                }
            }



        }
        var queue = [];
        var draining = false;
        var currentQueue;
        var queueIndex = -1;

        function cleanUpNextTick() {
            if (!draining || !currentQueue) {
                return;
            }
            draining = false;
            if (currentQueue.length) {
                queue = currentQueue.concat(queue);
            } else {
                queueIndex = -1;
            }
            if (queue.length) {
                drainQueue();
            }
        }

        function drainQueue() {
            if (draining) {
                return;
            }
            var timeout = runTimeout(cleanUpNextTick);
            draining = true;

            var len = queue.length;
            while(len) {
                currentQueue = queue;
                queue = [];
                while (++queueIndex < len) {
                    if (currentQueue) {
                        currentQueue[queueIndex].run();
                    }
                }
                queueIndex = -1;
                len = queue.length;
            }
            currentQueue = null;
            draining = false;
            runClearTimeout(timeout);
        }

        process.nextTick = function (fun) {
            var args = new Array(arguments.length - 1);
            if (arguments.length > 1) {
                for (var i = 1; i < arguments.length; i++) {
                    args[i - 1] = arguments[i];
                }
            }
            queue.push(new Item(fun, args));
            if (queue.length === 1 && !draining) {
                runTimeout(drainQueue);
            }
        };

// v8 likes predictible objects
        function Item(fun, array) {
            this.fun = fun;
            this.array = array;
        }
        Item.prototype.run = function () {
            this.fun.apply(null, this.array);
        };
        process.title = 'browser';
        process.browser = true;
        process.env = {};
        process.argv = [];
        process.version = ''; // empty string to avoid regexp issues
        process.versions = {};

        function noop() {}

        process.on = noop;
        process.addListener = noop;
        process.once = noop;
        process.off = noop;
        process.removeListener = noop;
        process.removeAllListeners = noop;
        process.emit = noop;
        process.prependListener = noop;
        process.prependOnceListener = noop;

        process.listeners = function (name) { return [] }

        process.binding = function (name) {
            throw new Error('process.binding is not supported');
        };

        process.cwd = function () { return '/' };
        process.chdir = function (dir) {
            throw new Error('process.chdir is not supported');
        };
        process.umask = function() { return 0; };


        /***/ }),

    /***/ "./node_modules/socket.io-client/lib/index.js":
    /*!****************************************************!*\
  !*** ./node_modules/socket.io-client/lib/index.js ***!
  \****************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Module dependencies.
         */

        var url = __webpack_require__(/*! ./url */ "./node_modules/socket.io-client/lib/url.js");
        var parser = __webpack_require__(/*! socket.io-parser */ "./node_modules/socket.io-parser/index.js");
        var Manager = __webpack_require__(/*! ./manager */ "./node_modules/socket.io-client/lib/manager.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('socket.io-client');

        /**
         * Module exports.
         */

        module.exports = exports = lookup;

        /**
         * Managers cache.
         */

        var cache = exports.managers = {};

        /**
         * Looks up an existing `Manager` for multiplexing.
         * If the user summons:
         *
         *   `io('http://localhost/a');`
         *   `io('http://localhost/b');`
         *
         * We reuse the existing instance based on same scheme/port/host,
         * and we initialize sockets for each namespace.
         *
         * @api public
         */

        function lookup (uri, opts) {
            if (typeof uri === 'object') {
                opts = uri;
                uri = undefined;
            }

            opts = opts || {};

            var parsed = url(uri);
            var source = parsed.source;
            var id = parsed.id;
            var path = parsed.path;
            var sameNamespace = cache[id] && path in cache[id].nsps;
            var newConnection = opts.forceNew || opts['force new connection'] ||
                false === opts.multiplex || sameNamespace;

            var io;

            if (newConnection) {
                debug('ignoring socket cache for %s', source);
                io = Manager(source, opts);
            } else {
                if (!cache[id]) {
                    debug('new io instance for %s', source);
                    cache[id] = Manager(source, opts);
                }
                io = cache[id];
            }
            if (parsed.query && !opts.query) {
                opts.query = parsed.query;
            }
            return io.socket(parsed.path, opts);
        }

        /**
         * Protocol version.
         *
         * @api public
         */

        exports.protocol = parser.protocol;

        /**
         * `connect`.
         *
         * @param {String} uri
         * @api public
         */

        exports.connect = lookup;

        /**
         * Expose constructors for standalone build.
         *
         * @api public
         */

        exports.Manager = __webpack_require__(/*! ./manager */ "./node_modules/socket.io-client/lib/manager.js");
        exports.Socket = __webpack_require__(/*! ./socket */ "./node_modules/socket.io-client/lib/socket.js");


        /***/ }),

    /***/ "./node_modules/socket.io-client/lib/manager.js":
    /*!******************************************************!*\
  !*** ./node_modules/socket.io-client/lib/manager.js ***!
  \******************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Module dependencies.
         */

        var eio = __webpack_require__(/*! engine.io-client */ "./node_modules/engine.io-client/lib/index.js");
        var Socket = __webpack_require__(/*! ./socket */ "./node_modules/socket.io-client/lib/socket.js");
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");
        var parser = __webpack_require__(/*! socket.io-parser */ "./node_modules/socket.io-parser/index.js");
        var on = __webpack_require__(/*! ./on */ "./node_modules/socket.io-client/lib/on.js");
        var bind = __webpack_require__(/*! component-bind */ "./node_modules/component-bind/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('socket.io-client:manager');
        var indexOf = __webpack_require__(/*! indexof */ "./node_modules/indexof/index.js");
        var Backoff = __webpack_require__(/*! backo2 */ "./node_modules/backo2/index.js");

        /**
         * IE6+ hasOwnProperty
         */

        var has = Object.prototype.hasOwnProperty;

        /**
         * Module exports
         */

        module.exports = Manager;

        /**
         * `Manager` constructor.
         *
         * @param {String} engine instance or engine uri/opts
         * @param {Object} options
         * @api public
         */

        function Manager (uri, opts) {
            if (!(this instanceof Manager)) return new Manager(uri, opts);
            if (uri && ('object' === typeof uri)) {
                opts = uri;
                uri = undefined;
            }
            opts = opts || {};

            opts.path = opts.path || '/socket.io';
            this.nsps = {};
            this.subs = [];
            this.opts = opts;
            this.reconnection(opts.reconnection !== false);
            this.reconnectionAttempts(opts.reconnectionAttempts || Infinity);
            this.reconnectionDelay(opts.reconnectionDelay || 1000);
            this.reconnectionDelayMax(opts.reconnectionDelayMax || 5000);
            this.randomizationFactor(opts.randomizationFactor || 0.5);
            this.backoff = new Backoff({
                min: this.reconnectionDelay(),
                max: this.reconnectionDelayMax(),
                jitter: this.randomizationFactor()
            });
            this.timeout(null == opts.timeout ? 20000 : opts.timeout);
            this.readyState = 'closed';
            this.uri = uri;
            this.connecting = [];
            this.lastPing = null;
            this.encoding = false;
            this.packetBuffer = [];
            var _parser = opts.parser || parser;
            this.encoder = new _parser.Encoder();
            this.decoder = new _parser.Decoder();
            this.autoConnect = opts.autoConnect !== false;
            if (this.autoConnect) this.open();
        }

        /**
         * Propagate given event to sockets and emit on `this`
         *
         * @api private
         */

        Manager.prototype.emitAll = function () {
            this.emit.apply(this, arguments);
            for (var nsp in this.nsps) {
                if (has.call(this.nsps, nsp)) {
                    this.nsps[nsp].emit.apply(this.nsps[nsp], arguments);
                }
            }
        };

        /**
         * Update `socket.id` of all sockets
         *
         * @api private
         */

        Manager.prototype.updateSocketIds = function () {
            for (var nsp in this.nsps) {
                if (has.call(this.nsps, nsp)) {
                    this.nsps[nsp].id = this.generateId(nsp);
                }
            }
        };

        /**
         * generate `socket.id` for the given `nsp`
         *
         * @param {String} nsp
         * @return {String}
         * @api private
         */

        Manager.prototype.generateId = function (nsp) {
            return (nsp === '/' ? '' : (nsp + '#')) + this.engine.id;
        };

        /**
         * Mix in `Emitter`.
         */

        Emitter(Manager.prototype);

        /**
         * Sets the `reconnection` config.
         *
         * @param {Boolean} true/false if it should automatically reconnect
         * @return {Manager} self or value
         * @api public
         */

        Manager.prototype.reconnection = function (v) {
            if (!arguments.length) return this._reconnection;
            this._reconnection = !!v;
            return this;
        };

        /**
         * Sets the reconnection attempts config.
         *
         * @param {Number} max reconnection attempts before giving up
         * @return {Manager} self or value
         * @api public
         */

        Manager.prototype.reconnectionAttempts = function (v) {
            if (!arguments.length) return this._reconnectionAttempts;
            this._reconnectionAttempts = v;
            return this;
        };

        /**
         * Sets the delay between reconnections.
         *
         * @param {Number} delay
         * @return {Manager} self or value
         * @api public
         */

        Manager.prototype.reconnectionDelay = function (v) {
            if (!arguments.length) return this._reconnectionDelay;
            this._reconnectionDelay = v;
            this.backoff && this.backoff.setMin(v);
            return this;
        };

        Manager.prototype.randomizationFactor = function (v) {
            if (!arguments.length) return this._randomizationFactor;
            this._randomizationFactor = v;
            this.backoff && this.backoff.setJitter(v);
            return this;
        };

        /**
         * Sets the maximum delay between reconnections.
         *
         * @param {Number} delay
         * @return {Manager} self or value
         * @api public
         */

        Manager.prototype.reconnectionDelayMax = function (v) {
            if (!arguments.length) return this._reconnectionDelayMax;
            this._reconnectionDelayMax = v;
            this.backoff && this.backoff.setMax(v);
            return this;
        };

        /**
         * Sets the connection timeout. `false` to disable
         *
         * @return {Manager} self or value
         * @api public
         */

        Manager.prototype.timeout = function (v) {
            if (!arguments.length) return this._timeout;
            this._timeout = v;
            return this;
        };

        /**
         * Starts trying to reconnect if reconnection is enabled and we have not
         * started reconnecting yet
         *
         * @api private
         */

        Manager.prototype.maybeReconnectOnOpen = function () {
            // Only try to reconnect if it's the first time we're connecting
            if (!this.reconnecting && this._reconnection && this.backoff.attempts === 0) {
                // keeps reconnection from firing twice for the same reconnection loop
                this.reconnect();
            }
        };

        /**
         * Sets the current transport `socket`.
         *
         * @param {Function} optional, callback
         * @return {Manager} self
         * @api public
         */

        Manager.prototype.open =
            Manager.prototype.connect = function (fn, opts) {
                debug('readyState %s', this.readyState);
                if (~this.readyState.indexOf('open')) return this;

                debug('opening %s', this.uri);
                this.engine = eio(this.uri, this.opts);
                var socket = this.engine;
                var self = this;
                this.readyState = 'opening';
                this.skipReconnect = false;

                // emit `open`
                var openSub = on(socket, 'open', function () {
                    self.onopen();
                    fn && fn();
                });

                // emit `connect_error`
                var errorSub = on(socket, 'error', function (data) {
                    debug('connect_error');
                    self.cleanup();
                    self.readyState = 'closed';
                    self.emitAll('connect_error', data);
                    if (fn) {
                        var err = new Error('Connection error');
                        err.data = data;
                        fn(err);
                    } else {
                        // Only do this if there is no fn to handle the error
                        self.maybeReconnectOnOpen();
                    }
                });

                // emit `connect_timeout`
                if (false !== this._timeout) {
                    var timeout = this._timeout;
                    debug('connect attempt will timeout after %d', timeout);

                    if (timeout === 0) {
                        openSub.destroy(); // prevents a race condition with the 'open' event
                    }

                    // set timer
                    var timer = setTimeout(function () {
                        debug('connect attempt timed out after %d', timeout);
                        openSub.destroy();
                        socket.close();
                        socket.emit('error', 'timeout');
                        self.emitAll('connect_timeout', timeout);
                    }, timeout);

                    this.subs.push({
                        destroy: function () {
                            clearTimeout(timer);
                        }
                    });
                }

                this.subs.push(openSub);
                this.subs.push(errorSub);

                return this;
            };

        /**
         * Called upon transport open.
         *
         * @api private
         */

        Manager.prototype.onopen = function () {
            debug('open');

            // clear old subs
            this.cleanup();

            // mark as open
            this.readyState = 'open';
            this.emit('open');

            // add new subs
            var socket = this.engine;
            this.subs.push(on(socket, 'data', bind(this, 'ondata')));
            this.subs.push(on(socket, 'ping', bind(this, 'onping')));
            this.subs.push(on(socket, 'pong', bind(this, 'onpong')));
            this.subs.push(on(socket, 'error', bind(this, 'onerror')));
            this.subs.push(on(socket, 'close', bind(this, 'onclose')));
            this.subs.push(on(this.decoder, 'decoded', bind(this, 'ondecoded')));
        };

        /**
         * Called upon a ping.
         *
         * @api private
         */

        Manager.prototype.onping = function () {
            this.lastPing = new Date();
            this.emitAll('ping');
        };

        /**
         * Called upon a packet.
         *
         * @api private
         */

        Manager.prototype.onpong = function () {
            this.emitAll('pong', new Date() - this.lastPing);
        };

        /**
         * Called with data.
         *
         * @api private
         */

        Manager.prototype.ondata = function (data) {
            this.decoder.add(data);
        };

        /**
         * Called when parser fully decodes a packet.
         *
         * @api private
         */

        Manager.prototype.ondecoded = function (packet) {
            this.emit('packet', packet);
        };

        /**
         * Called upon socket error.
         *
         * @api private
         */

        Manager.prototype.onerror = function (err) {
            debug('error', err);
            this.emitAll('error', err);
        };

        /**
         * Creates a new socket for the given `nsp`.
         *
         * @return {Socket}
         * @api public
         */

        Manager.prototype.socket = function (nsp, opts) {
            var socket = this.nsps[nsp];
            if (!socket) {
                socket = new Socket(this, nsp, opts);
                this.nsps[nsp] = socket;
                var self = this;
                socket.on('connecting', onConnecting);
                socket.on('connect', function () {
                    socket.id = self.generateId(nsp);
                });

                if (this.autoConnect) {
                    // manually call here since connecting event is fired before listening
                    onConnecting();
                }
            }

            function onConnecting () {
                if (!~indexOf(self.connecting, socket)) {
                    self.connecting.push(socket);
                }
            }

            return socket;
        };

        /**
         * Called upon a socket close.
         *
         * @param {Socket} socket
         */

        Manager.prototype.destroy = function (socket) {
            var index = indexOf(this.connecting, socket);
            if (~index) this.connecting.splice(index, 1);
            if (this.connecting.length) return;

            this.close();
        };

        /**
         * Writes a packet.
         *
         * @param {Object} packet
         * @api private
         */

        Manager.prototype.packet = function (packet) {
            debug('writing packet %j', packet);
            var self = this;
            if (packet.query && packet.type === 0) packet.nsp += '?' + packet.query;

            if (!self.encoding) {
                // encode, then write to engine with result
                self.encoding = true;
                this.encoder.encode(packet, function (encodedPackets) {
                    for (var i = 0; i < encodedPackets.length; i++) {
                        self.engine.write(encodedPackets[i], packet.options);
                    }
                    self.encoding = false;
                    self.processPacketQueue();
                });
            } else { // add packet to the queue
                self.packetBuffer.push(packet);
            }
        };

        /**
         * If packet buffer is non-empty, begins encoding the
         * next packet in line.
         *
         * @api private
         */

        Manager.prototype.processPacketQueue = function () {
            if (this.packetBuffer.length > 0 && !this.encoding) {
                var pack = this.packetBuffer.shift();
                this.packet(pack);
            }
        };

        /**
         * Clean up transport subscriptions and packet buffer.
         *
         * @api private
         */

        Manager.prototype.cleanup = function () {
            debug('cleanup');

            var subsLength = this.subs.length;
            for (var i = 0; i < subsLength; i++) {
                var sub = this.subs.shift();
                sub.destroy();
            }

            this.packetBuffer = [];
            this.encoding = false;
            this.lastPing = null;

            this.decoder.destroy();
        };

        /**
         * Close the current socket.
         *
         * @api private
         */

        Manager.prototype.close =
            Manager.prototype.disconnect = function () {
                debug('disconnect');
                this.skipReconnect = true;
                this.reconnecting = false;
                if ('opening' === this.readyState) {
                    // `onclose` will not fire because
                    // an open event never happened
                    this.cleanup();
                }
                this.backoff.reset();
                this.readyState = 'closed';
                if (this.engine) this.engine.close();
            };

        /**
         * Called upon engine close.
         *
         * @api private
         */

        Manager.prototype.onclose = function (reason) {
            debug('onclose');

            this.cleanup();
            this.backoff.reset();
            this.readyState = 'closed';
            this.emit('close', reason);

            if (this._reconnection && !this.skipReconnect) {
                this.reconnect();
            }
        };

        /**
         * Attempt a reconnection.
         *
         * @api private
         */

        Manager.prototype.reconnect = function () {
            if (this.reconnecting || this.skipReconnect) return this;

            var self = this;

            if (this.backoff.attempts >= this._reconnectionAttempts) {
                debug('reconnect failed');
                this.backoff.reset();
                this.emitAll('reconnect_failed');
                this.reconnecting = false;
            } else {
                var delay = this.backoff.duration();
                debug('will wait %dms before reconnect attempt', delay);

                this.reconnecting = true;
                var timer = setTimeout(function () {
                    if (self.skipReconnect) return;

                    debug('attempting reconnect');
                    self.emitAll('reconnect_attempt', self.backoff.attempts);
                    self.emitAll('reconnecting', self.backoff.attempts);

                    // check again for the case socket closed in above events
                    if (self.skipReconnect) return;

                    self.open(function (err) {
                        if (err) {
                            debug('reconnect attempt error');
                            self.reconnecting = false;
                            self.reconnect();
                            self.emitAll('reconnect_error', err.data);
                        } else {
                            debug('reconnect success');
                            self.onreconnect();
                        }
                    });
                }, delay);

                this.subs.push({
                    destroy: function () {
                        clearTimeout(timer);
                    }
                });
            }
        };

        /**
         * Called upon successful reconnect.
         *
         * @api private
         */

        Manager.prototype.onreconnect = function () {
            var attempt = this.backoff.attempts;
            this.reconnecting = false;
            this.backoff.reset();
            this.updateSocketIds();
            this.emitAll('reconnect', attempt);
        };


        /***/ }),

    /***/ "./node_modules/socket.io-client/lib/on.js":
    /*!*************************************************!*\
  !*** ./node_modules/socket.io-client/lib/on.js ***!
  \*************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {


        /**
         * Module exports.
         */

        module.exports = on;

        /**
         * Helper for subscriptions.
         *
         * @param {Object|EventEmitter} obj with `Emitter` mixin or `EventEmitter`
         * @param {String} event name
         * @param {Function} callback
         * @api public
         */

        function on (obj, ev, fn) {
            obj.on(ev, fn);
            return {
                destroy: function () {
                    obj.removeListener(ev, fn);
                }
            };
        }


        /***/ }),

    /***/ "./node_modules/socket.io-client/lib/socket.js":
    /*!*****************************************************!*\
  !*** ./node_modules/socket.io-client/lib/socket.js ***!
  \*****************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Module dependencies.
         */

        var parser = __webpack_require__(/*! socket.io-parser */ "./node_modules/socket.io-parser/index.js");
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");
        var toArray = __webpack_require__(/*! to-array */ "./node_modules/to-array/index.js");
        var on = __webpack_require__(/*! ./on */ "./node_modules/socket.io-client/lib/on.js");
        var bind = __webpack_require__(/*! component-bind */ "./node_modules/component-bind/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('socket.io-client:socket');
        var parseqs = __webpack_require__(/*! parseqs */ "./node_modules/socket.io-client/node_modules/parseqs/index.js");
        var hasBin = __webpack_require__(/*! has-binary2 */ "./node_modules/has-binary2/index.js");

        /**
         * Module exports.
         */

        module.exports = exports = Socket;

        /**
         * Internal events (blacklisted).
         * These events can't be emitted by the user.
         *
         * @api private
         */

        var events = {
            connect: 1,
            connect_error: 1,
            connect_timeout: 1,
            connecting: 1,
            disconnect: 1,
            error: 1,
            reconnect: 1,
            reconnect_attempt: 1,
            reconnect_failed: 1,
            reconnect_error: 1,
            reconnecting: 1,
            ping: 1,
            pong: 1
        };

        /**
         * Shortcut to `Emitter#emit`.
         */

        var emit = Emitter.prototype.emit;

        /**
         * `Socket` constructor.
         *
         * @api public
         */

        function Socket (io, nsp, opts) {
            this.io = io;
            this.nsp = nsp;
            this.json = this; // compat
            this.ids = 0;
            this.acks = {};
            this.receiveBuffer = [];
            this.sendBuffer = [];
            this.connected = false;
            this.disconnected = true;
            this.flags = {};
            if (opts && opts.query) {
                this.query = opts.query;
            }
            if (this.io.autoConnect) this.open();
        }

        /**
         * Mix in `Emitter`.
         */

        Emitter(Socket.prototype);

        /**
         * Subscribe to open, close and packet events
         *
         * @api private
         */

        Socket.prototype.subEvents = function () {
            if (this.subs) return;

            var io = this.io;
            this.subs = [
                on(io, 'open', bind(this, 'onopen')),
                on(io, 'packet', bind(this, 'onpacket')),
                on(io, 'close', bind(this, 'onclose'))
            ];
        };

        /**
         * "Opens" the socket.
         *
         * @api public
         */

        Socket.prototype.open =
            Socket.prototype.connect = function () {
                if (this.connected) return this;

                this.subEvents();
                if (!this.io.reconnecting) this.io.open(); // ensure open
                if ('open' === this.io.readyState) this.onopen();
                this.emit('connecting');
                return this;
            };

        /**
         * Sends a `message` event.
         *
         * @return {Socket} self
         * @api public
         */

        Socket.prototype.send = function () {
            var args = toArray(arguments);
            args.unshift('message');
            this.emit.apply(this, args);
            return this;
        };

        /**
         * Override `emit`.
         * If the event is in `events`, it's emitted normally.
         *
         * @param {String} event name
         * @return {Socket} self
         * @api public
         */

        Socket.prototype.emit = function (ev) {
            if (events.hasOwnProperty(ev)) {
                emit.apply(this, arguments);
                return this;
            }

            var args = toArray(arguments);
            var packet = {
                type: (this.flags.binary !== undefined ? this.flags.binary : hasBin(args)) ? parser.BINARY_EVENT : parser.EVENT,
                data: args
            };

            packet.options = {};
            packet.options.compress = !this.flags || false !== this.flags.compress;

            // event ack callback
            if ('function' === typeof args[args.length - 1]) {
                debug('emitting packet with ack id %d', this.ids);
                this.acks[this.ids] = args.pop();
                packet.id = this.ids++;
            }

            if (this.connected) {
                this.packet(packet);
            } else {
                this.sendBuffer.push(packet);
            }

            this.flags = {};

            return this;
        };

        /**
         * Sends a packet.
         *
         * @param {Object} packet
         * @api private
         */

        Socket.prototype.packet = function (packet) {
            packet.nsp = this.nsp;
            this.io.packet(packet);
        };

        /**
         * Called upon engine `open`.
         *
         * @api private
         */

        Socket.prototype.onopen = function () {
            debug('transport is open - connecting');

            // write connect packet if necessary
            if ('/' !== this.nsp) {
                if (this.query) {
                    var query = typeof this.query === 'object' ? parseqs.encode(this.query) : this.query;
                    debug('sending connect packet with query %s', query);
                    this.packet({type: parser.CONNECT, query: query});
                } else {
                    this.packet({type: parser.CONNECT});
                }
            }
        };

        /**
         * Called upon engine `close`.
         *
         * @param {String} reason
         * @api private
         */

        Socket.prototype.onclose = function (reason) {
            debug('close (%s)', reason);
            this.connected = false;
            this.disconnected = true;
            delete this.id;
            this.emit('disconnect', reason);
        };

        /**
         * Called with socket packet.
         *
         * @param {Object} packet
         * @api private
         */

        Socket.prototype.onpacket = function (packet) {
            var sameNamespace = packet.nsp === this.nsp;
            var rootNamespaceError = packet.type === parser.ERROR && packet.nsp === '/';

            if (!sameNamespace && !rootNamespaceError) return;

            switch (packet.type) {
                case parser.CONNECT:
                    this.onconnect();
                    break;

                case parser.EVENT:
                    this.onevent(packet);
                    break;

                case parser.BINARY_EVENT:
                    this.onevent(packet);
                    break;

                case parser.ACK:
                    this.onack(packet);
                    break;

                case parser.BINARY_ACK:
                    this.onack(packet);
                    break;

                case parser.DISCONNECT:
                    this.ondisconnect();
                    break;

                case parser.ERROR:
                    this.emit('error', packet.data);
                    break;
            }
        };

        /**
         * Called upon a server event.
         *
         * @param {Object} packet
         * @api private
         */

        Socket.prototype.onevent = function (packet) {
            var args = packet.data || [];
            debug('emitting event %j', args);

            if (null != packet.id) {
                debug('attaching ack callback to event');
                args.push(this.ack(packet.id));
            }

            if (this.connected) {
                emit.apply(this, args);
            } else {
                this.receiveBuffer.push(args);
            }
        };

        /**
         * Produces an ack callback to emit with an event.
         *
         * @api private
         */

        Socket.prototype.ack = function (id) {
            var self = this;
            var sent = false;
            return function () {
                // prevent double callbacks
                if (sent) return;
                sent = true;
                var args = toArray(arguments);
                debug('sending ack %j', args);

                self.packet({
                    type: hasBin(args) ? parser.BINARY_ACK : parser.ACK,
                    id: id,
                    data: args
                });
            };
        };

        /**
         * Called upon a server acknowlegement.
         *
         * @param {Object} packet
         * @api private
         */

        Socket.prototype.onack = function (packet) {
            var ack = this.acks[packet.id];
            if ('function' === typeof ack) {
                debug('calling ack %s with %j', packet.id, packet.data);
                ack.apply(this, packet.data);
                delete this.acks[packet.id];
            } else {
                debug('bad ack %s', packet.id);
            }
        };

        /**
         * Called upon server connect.
         *
         * @api private
         */

        Socket.prototype.onconnect = function () {
            this.connected = true;
            this.disconnected = false;
            this.emit('connect');
            this.emitBuffered();
        };

        /**
         * Emit buffered events (received and emitted).
         *
         * @api private
         */

        Socket.prototype.emitBuffered = function () {
            var i;
            for (i = 0; i < this.receiveBuffer.length; i++) {
                emit.apply(this, this.receiveBuffer[i]);
            }
            this.receiveBuffer = [];

            for (i = 0; i < this.sendBuffer.length; i++) {
                this.packet(this.sendBuffer[i]);
            }
            this.sendBuffer = [];
        };

        /**
         * Called upon server disconnect.
         *
         * @api private
         */

        Socket.prototype.ondisconnect = function () {
            debug('server disconnect (%s)', this.nsp);
            this.destroy();
            this.onclose('io server disconnect');
        };

        /**
         * Called upon forced client/server side disconnections,
         * this method ensures the manager stops tracking us and
         * that reconnections don't get triggered for this.
         *
         * @api private.
         */

        Socket.prototype.destroy = function () {
            if (this.subs) {
                // clean subscriptions to avoid reconnections
                for (var i = 0; i < this.subs.length; i++) {
                    this.subs[i].destroy();
                }
                this.subs = null;
            }

            this.io.destroy(this);
        };

        /**
         * Disconnects the socket manually.
         *
         * @return {Socket} self
         * @api public
         */

        Socket.prototype.close =
            Socket.prototype.disconnect = function () {
                if (this.connected) {
                    debug('performing disconnect (%s)', this.nsp);
                    this.packet({ type: parser.DISCONNECT });
                }

                // remove socket from pool
                this.destroy();

                if (this.connected) {
                    // fire events
                    this.onclose('io client disconnect');
                }
                return this;
            };

        /**
         * Sets the compress flag.
         *
         * @param {Boolean} if `true`, compresses the sending data
         * @return {Socket} self
         * @api public
         */

        Socket.prototype.compress = function (compress) {
            this.flags.compress = compress;
            return this;
        };

        /**
         * Sets the binary flag
         *
         * @param {Boolean} whether the emitted data contains binary
         * @return {Socket} self
         * @api public
         */

        Socket.prototype.binary = function (binary) {
            this.flags.binary = binary;
            return this;
        };


        /***/ }),

    /***/ "./node_modules/socket.io-client/lib/url.js":
    /*!**************************************************!*\
  !*** ./node_modules/socket.io-client/lib/url.js ***!
  \**************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Module dependencies.
         */

        var parseuri = __webpack_require__(/*! parseuri */ "./node_modules/socket.io-client/node_modules/parseuri/index.js");
        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('socket.io-client:url');

        /**
         * Module exports.
         */

        module.exports = url;

        /**
         * URL parser.
         *
         * @param {String} url
         * @param {Object} An object meant to mimic window.location.
         *                 Defaults to window.location.
         * @api public
         */

        function url (uri, loc) {
            var obj = uri;

            // default to window.location
            loc = loc || (typeof location !== 'undefined' && location);
            if (null == uri) uri = loc.protocol + '//' + loc.host;

            // relative path support
            if ('string' === typeof uri) {
                if ('/' === uri.charAt(0)) {
                    if ('/' === uri.charAt(1)) {
                        uri = loc.protocol + uri;
                    } else {
                        uri = loc.host + uri;
                    }
                }

                if (!/^(https?|wss?):\/\//.test(uri)) {
                    debug('protocol-less url %s', uri);
                    if ('undefined' !== typeof loc) {
                        uri = loc.protocol + '//' + uri;
                    } else {
                        uri = 'https://' + uri;
                    }
                }

                // parse
                debug('parse %s', uri);
                obj = parseuri(uri);
            }

            // make sure we treat `localhost:80` and `localhost` equally
            if (!obj.port) {
                if (/^(http|ws)$/.test(obj.protocol)) {
                    obj.port = '80';
                } else if (/^(http|ws)s$/.test(obj.protocol)) {
                    obj.port = '443';
                }
            }

            obj.path = obj.path || '/';

            var ipv6 = obj.host.indexOf(':') !== -1;
            var host = ipv6 ? '[' + obj.host + ']' : obj.host;

            // define unique id
            obj.id = obj.protocol + '://' + host + ':' + obj.port;
            // define href
            obj.href = obj.protocol + '://' + host + (loc && loc.port === obj.port ? '' : (':' + obj.port));

            return obj;
        }


        /***/ }),

    /***/ "./node_modules/socket.io-client/node_modules/parseqs/index.js":
    /*!*********************************************************************!*\
  !*** ./node_modules/socket.io-client/node_modules/parseqs/index.js ***!
  \*********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Compiles a querystring
         * Returns string representation of the object
         *
         * @param {Object}
         * @api private
         */

        exports.encode = function (obj) {
            var str = '';

            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    if (str.length) str += '&';
                    str += encodeURIComponent(i) + '=' + encodeURIComponent(obj[i]);
                }
            }

            return str;
        };

        /**
         * Parses a simple querystring into an object
         *
         * @param {String} qs
         * @api private
         */

        exports.decode = function(qs){
            var qry = {};
            var pairs = qs.split('&');
            for (var i = 0, l = pairs.length; i < l; i++) {
                var pair = pairs[i].split('=');
                qry[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            return qry;
        };


        /***/ }),

    /***/ "./node_modules/socket.io-client/node_modules/parseuri/index.js":
    /*!**********************************************************************!*\
  !*** ./node_modules/socket.io-client/node_modules/parseuri/index.js ***!
  \**********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /**
         * Parses an URI
         *
         * @author Steven Levithan <stevenlevithan.com> (MIT license)
         * @api private
         */

        var re = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;

        var parts = [
            'source', 'protocol', 'authority', 'userInfo', 'user', 'password', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'anchor'
        ];

        module.exports = function parseuri(str) {
            var src = str,
                b = str.indexOf('['),
                e = str.indexOf(']');

            if (b != -1 && e != -1) {
                str = str.substring(0, b) + str.substring(b, e).replace(/:/g, ';') + str.substring(e, str.length);
            }

            var m = re.exec(str || ''),
                uri = {},
                i = 14;

            while (i--) {
                uri[parts[i]] = m[i] || '';
            }

            if (b != -1 && e != -1) {
                uri.source = src;
                uri.host = uri.host.substring(1, uri.host.length - 1).replace(/;/g, ':');
                uri.authority = uri.authority.replace('[', '').replace(']', '').replace(/;/g, ':');
                uri.ipv6uri = true;
            }

            uri.pathNames = pathNames(uri, uri['path']);
            uri.queryKey = queryKey(uri, uri['query']);

            return uri;
        };

        function pathNames(obj, path) {
            var regx = /\/{2,9}/g,
                names = path.replace(regx, "/").split("/");

            if (path.substr(0, 1) == '/' || path.length === 0) {
                names.splice(0, 1);
            }
            if (path.substr(path.length - 1, 1) == '/') {
                names.splice(names.length - 1, 1);
            }

            return names;
        }

        function queryKey(uri, query) {
            var data = {};

            query.replace(/(?:^|&)([^&=]*)=?([^&]*)/g, function ($0, $1, $2) {
                if ($1) {
                    data[$1] = $2;
                }
            });

            return data;
        }


        /***/ }),

    /***/ "./node_modules/socket.io-parser/binary.js":
    /*!*************************************************!*\
  !*** ./node_modules/socket.io-parser/binary.js ***!
  \*************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /*global Blob,File*/

        /**
         * Module requirements
         */

        var isArray = __webpack_require__(/*! isarray */ "./node_modules/socket.io-parser/node_modules/isarray/index.js");
        var isBuf = __webpack_require__(/*! ./is-buffer */ "./node_modules/socket.io-parser/is-buffer.js");
        var toString = Object.prototype.toString;
        var withNativeBlob = typeof Blob === 'function' || (typeof Blob !== 'undefined' && toString.call(Blob) === '[object BlobConstructor]');
        var withNativeFile = typeof File === 'function' || (typeof File !== 'undefined' && toString.call(File) === '[object FileConstructor]');

        /**
         * Replaces every Buffer | ArrayBuffer in packet with a numbered placeholder.
         * Anything with blobs or files should be fed through removeBlobs before coming
         * here.
         *
         * @param {Object} packet - socket.io event packet
         * @return {Object} with deconstructed packet and list of buffers
         * @api public
         */

        exports.deconstructPacket = function(packet) {
            var buffers = [];
            var packetData = packet.data;
            var pack = packet;
            pack.data = _deconstructPacket(packetData, buffers);
            pack.attachments = buffers.length; // number of binary 'attachments'
            return {packet: pack, buffers: buffers};
        };

        function _deconstructPacket(data, buffers) {
            if (!data) return data;

            if (isBuf(data)) {
                var placeholder = { _placeholder: true, num: buffers.length };
                buffers.push(data);
                return placeholder;
            } else if (isArray(data)) {
                var newData = new Array(data.length);
                for (var i = 0; i < data.length; i++) {
                    newData[i] = _deconstructPacket(data[i], buffers);
                }
                return newData;
            } else if (typeof data === 'object' && !(data instanceof Date)) {
                var newData = {};
                for (var key in data) {
                    newData[key] = _deconstructPacket(data[key], buffers);
                }
                return newData;
            }
            return data;
        }

        /**
         * Reconstructs a binary packet from its placeholder packet and buffers
         *
         * @param {Object} packet - event packet with placeholders
         * @param {Array} buffers - binary buffers to put in placeholder positions
         * @return {Object} reconstructed packet
         * @api public
         */

        exports.reconstructPacket = function(packet, buffers) {
            packet.data = _reconstructPacket(packet.data, buffers);
            packet.attachments = undefined; // no longer useful
            return packet;
        };

        function _reconstructPacket(data, buffers) {
            if (!data) return data;

            if (data && data._placeholder) {
                return buffers[data.num]; // appropriate buffer (should be natural order anyway)
            } else if (isArray(data)) {
                for (var i = 0; i < data.length; i++) {
                    data[i] = _reconstructPacket(data[i], buffers);
                }
            } else if (typeof data === 'object') {
                for (var key in data) {
                    data[key] = _reconstructPacket(data[key], buffers);
                }
            }

            return data;
        }

        /**
         * Asynchronously removes Blobs or Files from data via
         * FileReader's readAsArrayBuffer method. Used before encoding
         * data as msgpack. Calls callback with the blobless data.
         *
         * @param {Object} data
         * @param {Function} callback
         * @api private
         */

        exports.removeBlobs = function(data, callback) {
            function _removeBlobs(obj, curKey, containingObject) {
                if (!obj) return obj;

                // convert any blob
                if ((withNativeBlob && obj instanceof Blob) ||
                    (withNativeFile && obj instanceof File)) {
                    pendingBlobs++;

                    // async filereader
                    var fileReader = new FileReader();
                    fileReader.onload = function() { // this.result == arraybuffer
                        if (containingObject) {
                            containingObject[curKey] = this.result;
                        }
                        else {
                            bloblessData = this.result;
                        }

                        // if nothing pending its callback time
                        if(! --pendingBlobs) {
                            callback(bloblessData);
                        }
                    };

                    fileReader.readAsArrayBuffer(obj); // blob -> arraybuffer
                } else if (isArray(obj)) { // handle array
                    for (var i = 0; i < obj.length; i++) {
                        _removeBlobs(obj[i], i, obj);
                    }
                } else if (typeof obj === 'object' && !isBuf(obj)) { // and object
                    for (var key in obj) {
                        _removeBlobs(obj[key], key, obj);
                    }
                }
            }

            var pendingBlobs = 0;
            var bloblessData = data;
            _removeBlobs(bloblessData);
            if (!pendingBlobs) {
                callback(bloblessData);
            }
        };


        /***/ }),

    /***/ "./node_modules/socket.io-parser/index.js":
    /*!************************************************!*\
  !*** ./node_modules/socket.io-parser/index.js ***!
  \************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {


        /**
         * Module dependencies.
         */

        var debug = __webpack_require__(/*! debug */ "./node_modules/debug/src/browser.js")('socket.io-parser');
        var Emitter = __webpack_require__(/*! component-emitter */ "./node_modules/component-emitter/index.js");
        var binary = __webpack_require__(/*! ./binary */ "./node_modules/socket.io-parser/binary.js");
        var isArray = __webpack_require__(/*! isarray */ "./node_modules/socket.io-parser/node_modules/isarray/index.js");
        var isBuf = __webpack_require__(/*! ./is-buffer */ "./node_modules/socket.io-parser/is-buffer.js");

        /**
         * Protocol version.
         *
         * @api public
         */

        exports.protocol = 4;

        /**
         * Packet types.
         *
         * @api public
         */

        exports.types = [
            'CONNECT',
            'DISCONNECT',
            'EVENT',
            'ACK',
            'ERROR',
            'BINARY_EVENT',
            'BINARY_ACK'
        ];

        /**
         * Packet type `connect`.
         *
         * @api public
         */

        exports.CONNECT = 0;

        /**
         * Packet type `disconnect`.
         *
         * @api public
         */

        exports.DISCONNECT = 1;

        /**
         * Packet type `event`.
         *
         * @api public
         */

        exports.EVENT = 2;

        /**
         * Packet type `ack`.
         *
         * @api public
         */

        exports.ACK = 3;

        /**
         * Packet type `error`.
         *
         * @api public
         */

        exports.ERROR = 4;

        /**
         * Packet type 'binary event'
         *
         * @api public
         */

        exports.BINARY_EVENT = 5;

        /**
         * Packet type `binary ack`. For acks with binary arguments.
         *
         * @api public
         */

        exports.BINARY_ACK = 6;

        /**
         * Encoder constructor.
         *
         * @api public
         */

        exports.Encoder = Encoder;

        /**
         * Decoder constructor.
         *
         * @api public
         */

        exports.Decoder = Decoder;

        /**
         * A socket.io Encoder instance
         *
         * @api public
         */

        function Encoder() {}

        var ERROR_PACKET = exports.ERROR + '"encode error"';

        /**
         * Encode a packet as a single string if non-binary, or as a
         * buffer sequence, depending on packet type.
         *
         * @param {Object} obj - packet object
         * @param {Function} callback - function to handle encodings (likely engine.write)
         * @return Calls callback with Array of encodings
         * @api public
         */

        Encoder.prototype.encode = function(obj, callback){
            debug('encoding packet %j', obj);

            if (exports.BINARY_EVENT === obj.type || exports.BINARY_ACK === obj.type) {
                encodeAsBinary(obj, callback);
            } else {
                var encoding = encodeAsString(obj);
                callback([encoding]);
            }
        };

        /**
         * Encode packet as string.
         *
         * @param {Object} packet
         * @return {String} encoded
         * @api private
         */

        function encodeAsString(obj) {

            // first is type
            var str = '' + obj.type;

            // attachments if we have them
            if (exports.BINARY_EVENT === obj.type || exports.BINARY_ACK === obj.type) {
                str += obj.attachments + '-';
            }

            // if we have a namespace other than `/`
            // we append it followed by a comma `,`
            if (obj.nsp && '/' !== obj.nsp) {
                str += obj.nsp + ',';
            }

            // immediately followed by the id
            if (null != obj.id) {
                str += obj.id;
            }

            // json data
            if (null != obj.data) {
                var payload = tryStringify(obj.data);
                if (payload !== false) {
                    str += payload;
                } else {
                    return ERROR_PACKET;
                }
            }

            debug('encoded %j as %s', obj, str);
            return str;
        }

        function tryStringify(str) {
            try {
                return JSON.stringify(str);
            } catch(e){
                return false;
            }
        }

        /**
         * Encode packet as 'buffer sequence' by removing blobs, and
         * deconstructing packet into object with placeholders and
         * a list of buffers.
         *
         * @param {Object} packet
         * @return {Buffer} encoded
         * @api private
         */

        function encodeAsBinary(obj, callback) {

            function writeEncoding(bloblessData) {
                var deconstruction = binary.deconstructPacket(bloblessData);
                var pack = encodeAsString(deconstruction.packet);
                var buffers = deconstruction.buffers;

                buffers.unshift(pack); // add packet info to beginning of data list
                callback(buffers); // write all the buffers
            }

            binary.removeBlobs(obj, writeEncoding);
        }

        /**
         * A socket.io Decoder instance
         *
         * @return {Object} decoder
         * @api public
         */

        function Decoder() {
            this.reconstructor = null;
        }

        /**
         * Mix in `Emitter` with Decoder.
         */

        Emitter(Decoder.prototype);

        /**
         * Decodes an encoded packet string into packet JSON.
         *
         * @param {String} obj - encoded packet
         * @return {Object} packet
         * @api public
         */

        Decoder.prototype.add = function(obj) {
            var packet;
            if (typeof obj === 'string') {
                packet = decodeString(obj);
                if (exports.BINARY_EVENT === packet.type || exports.BINARY_ACK === packet.type) { // binary packet's json
                    this.reconstructor = new BinaryReconstructor(packet);

                    // no attachments, labeled binary but no binary data to follow
                    if (this.reconstructor.reconPack.attachments === 0) {
                        this.emit('decoded', packet);
                    }
                } else { // non-binary full packet
                    this.emit('decoded', packet);
                }
            } else if (isBuf(obj) || obj.base64) { // raw binary data
                if (!this.reconstructor) {
                    throw new Error('got binary data when not reconstructing a packet');
                } else {
                    packet = this.reconstructor.takeBinaryData(obj);
                    if (packet) { // received final buffer
                        this.reconstructor = null;
                        this.emit('decoded', packet);
                    }
                }
            } else {
                throw new Error('Unknown type: ' + obj);
            }
        };

        /**
         * Decode a packet String (JSON data)
         *
         * @param {String} str
         * @return {Object} packet
         * @api private
         */

        function decodeString(str) {
            var i = 0;
            // look up type
            var p = {
                type: Number(str.charAt(0))
            };

            if (null == exports.types[p.type]) {
                return error('unknown packet type ' + p.type);
            }

            // look up attachments if type binary
            if (exports.BINARY_EVENT === p.type || exports.BINARY_ACK === p.type) {
                var start = i + 1;
                while (str.charAt(++i) !== '-' && i != str.length) {}
                var buf = str.substring(start, i);
                if (buf != Number(buf) || str.charAt(i) !== '-') {
                    throw new Error('Illegal attachments');
                }
                p.attachments = Number(buf);
            }

            // look up namespace (if any)
            if ('/' === str.charAt(i + 1)) {
                var start = i + 1;
                while (++i) {
                    var c = str.charAt(i);
                    if (',' === c) break;
                    if (i === str.length) break;
                }
                p.nsp = str.substring(start, i);
            } else {
                p.nsp = '/';
            }

            // look up id
            var next = str.charAt(i + 1);
            if ('' !== next && Number(next) == next) {
                var start = i + 1;
                while (++i) {
                    var c = str.charAt(i);
                    if (null == c || Number(c) != c) {
                        --i;
                        break;
                    }
                    if (i === str.length) break;
                }
                p.id = Number(str.substring(start, i + 1));
            }

            // look up json data
            if (str.charAt(++i)) {
                var payload = tryParse(str.substr(i));
                var isPayloadValid = payload !== false && (p.type === exports.ERROR || isArray(payload));
                if (isPayloadValid) {
                    p.data = payload;
                } else {
                    return error('invalid payload');
                }
            }

            debug('decoded %s as %j', str, p);
            return p;
        }

        function tryParse(str) {
            try {
                return JSON.parse(str);
            } catch(e){
                return false;
            }
        }

        /**
         * Deallocates a parser's resources
         *
         * @api public
         */

        Decoder.prototype.destroy = function() {
            if (this.reconstructor) {
                this.reconstructor.finishedReconstruction();
            }
        };

        /**
         * A manager of a binary event's 'buffer sequence'. Should
         * be constructed whenever a packet of type BINARY_EVENT is
         * decoded.
         *
         * @param {Object} packet
         * @return {BinaryReconstructor} initialized reconstructor
         * @api private
         */

        function BinaryReconstructor(packet) {
            this.reconPack = packet;
            this.buffers = [];
        }

        /**
         * Method to be called when binary data received from connection
         * after a BINARY_EVENT packet.
         *
         * @param {Buffer | ArrayBuffer} binData - the raw binary data received
         * @return {null | Object} returns null if more binary data is expected or
         *   a reconstructed packet object if all buffers have been received.
         * @api private
         */

        BinaryReconstructor.prototype.takeBinaryData = function(binData) {
            this.buffers.push(binData);
            if (this.buffers.length === this.reconPack.attachments) { // done with buffer list
                var packet = binary.reconstructPacket(this.reconPack, this.buffers);
                this.finishedReconstruction();
                return packet;
            }
            return null;
        };

        /**
         * Cleans up binary packet reconstruction variables.
         *
         * @api private
         */

        BinaryReconstructor.prototype.finishedReconstruction = function() {
            this.reconPack = null;
            this.buffers = [];
        };

        function error(msg) {
            return {
                type: exports.ERROR,
                data: 'parser error: ' + msg
            };
        }


        /***/ }),

    /***/ "./node_modules/socket.io-parser/is-buffer.js":
    /*!****************************************************!*\
  !*** ./node_modules/socket.io-parser/is-buffer.js ***!
  \****************************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        /* WEBPACK VAR INJECTION */(function(Buffer) {
            module.exports = isBuf;

            var withNativeBuffer = typeof Buffer === 'function' && typeof Buffer.isBuffer === 'function';
            var withNativeArrayBuffer = typeof ArrayBuffer === 'function';

            var isView = function (obj) {
                return typeof ArrayBuffer.isView === 'function' ? ArrayBuffer.isView(obj) : (obj.buffer instanceof ArrayBuffer);
            };

            /**
             * Returns true if obj is a buffer or an arraybuffer.
             *
             * @api private
             */

            function isBuf(obj) {
                return (withNativeBuffer && Buffer.isBuffer(obj)) ||
                    (withNativeArrayBuffer && (obj instanceof ArrayBuffer || isView(obj)));
            }

            /* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../buffer/index.js */ "./node_modules/buffer/index.js").Buffer))

        /***/ }),

    /***/ "./node_modules/socket.io-parser/node_modules/isarray/index.js":
    /*!*********************************************************************!*\
  !*** ./node_modules/socket.io-parser/node_modules/isarray/index.js ***!
  \*********************************************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        var toString = {}.toString;

        module.exports = Array.isArray || function (arr) {
            return toString.call(arr) == '[object Array]';
        };


        /***/ }),

    /***/ "./node_modules/to-array/index.js":
    /*!****************************************!*\
  !*** ./node_modules/to-array/index.js ***!
  \****************************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        module.exports = toArray

        function toArray(list, index) {
            var array = []

            index = index || 0

            for (var i = index || 0; i < list.length; i++) {
                array[i - index] = list[i]
            }

            return array
        }


        /***/ }),

    /***/ "./node_modules/webpack/buildin/global.js":
    /*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        var g;

// This works in non-strict mode
        g = (function() {
            return this;
        })();

        try {
            // This works if eval is allowed (see CSP)
            g = g || new Function("return this")();
        } catch (e) {
            // This works if the window reference is available
            if (typeof window === "object") g = window;
        }

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

        module.exports = g;


        /***/ }),

    /***/ "./node_modules/yeast/index.js":
    /*!*************************************!*\
  !*** ./node_modules/yeast/index.js ***!
  \*************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        "use strict";


        var alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_'.split('')
            , length = 64
            , map = {}
            , seed = 0
            , i = 0
            , prev;

        /**
         * Return a string representing the specified number.
         *
         * @param {Number} num The number to convert.
         * @returns {String} The string representation of the number.
         * @api public
         */
        function encode(num) {
            var encoded = '';

            do {
                encoded = alphabet[num % length] + encoded;
                num = Math.floor(num / length);
            } while (num > 0);

            return encoded;
        }

        /**
         * Return the integer value specified by the given string.
         *
         * @param {String} str The string to convert.
         * @returns {Number} The integer value represented by the string.
         * @api public
         */
        function decode(str) {
            var decoded = 0;

            for (i = 0; i < str.length; i++) {
                decoded = decoded * length + map[str.charAt(i)];
            }

            return decoded;
        }

        /**
         * Yeast: A tiny growing id generator.
         *
         * @returns {String} A unique id.
         * @api public
         */
        function yeast() {
            var now = encode(+new Date());

            if (now !== prev) return seed = 0, prev = now;
            return now +'.'+ encode(seed++);
        }

//
// Map each character to its index.
//
        for (; i < length; i++) map[alphabet[i]] = i;

//
// Expose the `yeast`, `encode` and `decode` functions.
//
        yeast.encode = encode;
        yeast.decode = decode;
        module.exports = yeast;


        /***/ }),

    /***/ "./resources/js/app-laravel.js":
    /*!*************************************!*\
  !*** ./resources/js/app-laravel.js ***!
  \*************************************/
    /*! no exports provided */
    /***/ (function(module, __webpack_exports__, __webpack_require__) {

        "use strict";
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var laravel_echo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! laravel-echo */ "./node_modules/laravel-echo/dist/echo.js");

        window.io = __webpack_require__(/*! socket.io-client */ "./node_modules/socket.io-client/lib/index.js");
        window.Echo = new laravel_echo__WEBPACK_IMPORTED_MODULE_0__["default"]({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        });
        console.log('import du fichier app-laravel.js by scireyhan-');

        /***/ }),

    /***/ 1:
    /*!*******************************************!*\
  !*** multi ./resources/js/app-laravel.js ***!
  \*******************************************/
    /*! no static exports found */
    /***/ (function(module, exports, __webpack_require__) {

        module.exports = __webpack_require__(/*! C:\wamp\www\glf\resources\js\app-laravel.js */"./resources/js/app-laravel.js");


        /***/ }),

    /***/ 2:
    /*!********************!*\
  !*** ws (ignored) ***!
  \********************/
    /*! no static exports found */
    /***/ (function(module, exports) {

        /* (ignored) */

        /***/ })

    /******/ });
