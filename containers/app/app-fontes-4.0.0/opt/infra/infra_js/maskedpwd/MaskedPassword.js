/*******************************************************************************
 MaskedPassword.js       Converts password fields into "masked" password fields
 ------------------------------------------------------------------------------
 Adapted from                                      FormTools.addPasswordMasking
 Info/Docs         http://www.brothercake.com/site/resources/scripts/formtools/
 ------------------------------------------------------------------------------
 Extraído de: https://www.sitepoint.com/better-passwords-1-the-masked-password-field/
 *******************************************************************************/



//masked password constructor
function MaskedPassword(passfield, symbol, fullmaskEnabled, spanClass)
{
    //if the browser is unsupported, silently fail
    //[pre-DOM1 browsers generally, and Opera 8 specifically]
    if(typeof document.getElementById == 'undefined'
        || typeof document.styleSheets == 'undefined') { return false; }

    //or if the passfield doesn't exist, silently fail
    if(passfield == null) { return false; }

    // por default, configura o componente como mascarando todas as letras
    // caso fullmaskEnabled seja false, o ultimo digito da senha aparecerá para o usuario
    this.fullmaskEnabled = fullmaskEnabled || true;

    if (spanClass == undefined){
        spanClass = '';
    }

    //save the masking symbol
    this.symbol = symbol;

    //identify Internet Explorer for a couple of conditions
    this.isIE = typeof document.uniqueID != 'undefined';

    //delete any default value for security (and simplicity!)
    passfield.value = '';
    passfield.defaultValue = '';

    //create a context wrapper, so that we have sole context for modifying the content
    //(ie. we can go context.innerHTML = replacement; without catering for
    // anything else that's there besides the password field itself)
    //and give it a distinctive and underscored name, to prevent conflict
    passfield._contextwrapper = this.createContextWrapper(passfield, spanClass);

    //create a fullmask flag which will be used from events to determine
    //whether to mask the entire password (true)
    //or to stop at the character limit (false)
    //most events set the flag before being called, except for onpropertychange
    //which uses whatever the setting currently is
    //this used to be an argument, but onpropertychange fires from our modifications
    //as well as manual input, so the blur event that's supposed to have a fullmask
    //triggers in turn an onpropertychange which doesn't, with the end result
    //that fullmask never works; so by doing it like this, we can set it to
    //true from the blur event and that will persist through all subsequent
    //onpropertychange events, until another manual event changes it back to false
    this.fullmask = this.fullmaskEnabled;// false;

    //for the code that converts a password field into a masked field
    //I used to have lovely clean elegant code for most browsers, then
    //ugly horrible hacky code for IE; but since the hacky approach does
    //actually work for everyone, and we have to have it here whatever,
    //we may as well just use it for everyone, and get a big saving in code-size
    //it also means we'll get total behavioral consistency, in terms of
    //the preservation (or rather, lack thereof) of existing event handlers

    //save a reference to the wrapper because the passfield reference will be lost soon
    var wrapper = passfield._contextwrapper;

    //create the HTML for the hidden field
    //using the name from the original password field
    var hiddenfield = '<input type="hidden" name="' + passfield.name + '">';

    //copy the HTML from the password field to create the new plain-text field
    var textfield = this.convertPasswordFieldHTML(passfield);

    //write the hiddenfield and textfield HTML into the wrapper, replacing what's there
    wrapper.innerHTML = hiddenfield + textfield;

    //grab back the passfield reference back and save it back to passfield
    //then add the masked-password class
    passfield = wrapper.lastChild;
    passfield.className += ' masked';

    //try to disable autocomplete for this field
    //to prevent firefox from remembering and subsequently offering
    //a menu of useless masking strings, things like "â?«â?«â?«â?«â?«â?«â?«f"
    //which of course can't be decoded, they'll just be represented by whatever
    //is in the realfield value at the time, ie. a completely unrelated value!
    passfield.setAttribute('autocomplete', 'off');

    //now grab the hidden field reference,
    //saving it as a property of the passfield
    passfield._realfield = wrapper.firstChild;

    //restore its contextwrapper reference
    passfield._contextwrapper = wrapper;

    //limit the caret position so that you can only edit or select from the end
    //you can't add, edit or select from the beginning or middle of the field
    //otherwise we can't track which masked characters represent which letters
    //(far from ideal I know, but I can't see how else to know
    //which masking symbols represent which letters if you edit from the middle..?)
    this.limitCaretPosition(passfield);

    //save a reference to this
    var self = this;

    //then apply the core events to the visible field
    this.addListener(passfield, 'change', function(e)
    {
        self.fullmask = self.fullmaskEnabled;//false;
        self.doPasswordMasking(self.getTarget(e));
    });
    this.addListener(passfield, 'input', function(e)
    {
        self.fullmask = self.fullmaskEnabled;//false;
        self.doPasswordMasking(self.getTarget(e));
    });
    //no fullmask setting for onpropertychange (as noted above)
    this.addListener(passfield, 'propertychange', function(e)
    {
        self.doPasswordMasking(self.getTarget(e));
    });

    //for keyup, don't respond to the tab or shift key, otherwise when you [shift/]tab
    //into the field the keyup will cause the fully-masked password to become partially masked
    //which is inconsistent with the mouse since it doesn't happen when you click focus
    //so it's only supposed to happen when you actually edit it; we'll also prevent it
    //from happening in response to arrows keys as well, for visual completeness!
    //and from the other modifiers keys, just cos it feels like the right thing to do
    this.addListener(passfield, 'keyup', function(e)
    {
        if(!/^(9|1[678]|224|3[789]|40)$/.test(e.keyCode.toString()))
        {
            self.fullmask = self.fullmaskEnabled;//false;
            self.doPasswordMasking(self.getTarget(e));
        }
    });

    //the blur event completely masks the input password
    //(as opposed to leaving the last n characters plain during input)
    this.addListener(passfield, 'blur', function(e)
    {
        self.fullmask = true;
        self.doPasswordMasking(self.getTarget(e));
    });

    //so between those events we get completely rock-solid behavior
    //with enough redundency to ensure that all input paths are covered
    //and no flickering of text between states :-)

    //force the parent form to reset onload
    //thereby clearing all values after soft refreh
    this.forceFormReset(passfield);

    //return true for success
    return true;
}


//associated utility methods
MaskedPassword.prototype =
    {

        //implement password masking for a textbox event
        doPasswordMasking : function(textbox)
        {
            //create the plain password string
            var plainpassword = '';

            //if we already have a real field value we need to work out the difference
            //between that and the value that's in the input field
            if(textbox._realfield.value != '')
            {
                //run through the characters in the input string
                //and build the plain password out of the corresponding characters
                //from the real field, and any plain characters in the input
                for(var i=0; i<textbox.value.length; i++)
                {
                    if(textbox.value.charAt(i) == this.symbol)
                    {
                        plainpassword += textbox._realfield.value.charAt(i);
                    }
                    else
                    {
                        plainpassword += textbox.value.charAt(i);
                    }
                }
            }

            //if there's no real field value then we're doing this for the first time
            //so whatever's in the input field is the entire plain password
            else
            {
                plainpassword = textbox.value;
            }

            //get the masked version of the plainpassword, according to fullmask
            //and passing the textbox reference so we have its symbol and limit properties
            var maskedstring = this.encodeMaskedPassword(plainpassword, this.fullmask, textbox);

            //then we modify the textfield values
            //if (AND ONLY IF) one of the values are now different from the original
            //(this condition is essential to avoid infinite repetition
            // leading to stack overflow, from onpropertychange in IE
            // [value changes, fires event, which changes value, which fires event ...])
            //we check both instead of just one, so that we can still allow the action
            //of changing the mask without modifying the password itself
            if(textbox._realfield.value != plainpassword || textbox.value != maskedstring)
            {
                //copy the plain password to the real field
                textbox._realfield.value = plainpassword;

                //then write the masked value to the original textbox
                textbox.value = maskedstring;
            }
        },


        //convert a plain-text password to a masked password
        encodeMaskedPassword : function(passwordstring, fullmask, textbox)
        {
            //the character limit is nominally 1
            //this is how many characters to leave plain at the end
            //but if the fullmask flag is true the limit is zero
            //and the password will be fully masked
            var characterlimit = fullmask === true ? 0 : 1;

            //create the masked password string then iterate
            //through he characters in the plain password
            for(var maskedstring = '', i=0; i<passwordstring.length; i++)
            {
                //if we're below the masking limit,
                //add a masking symbol to represent this character
                if(i < passwordstring.length - characterlimit)
                {
                    maskedstring += this.symbol;
                }
                //otherwise just copy across the real character
                else
                {
                    maskedstring += passwordstring.charAt(i);
                }
            }

            //return the final masked string
            return maskedstring;
        },


        //create a context wrapper element around a password field
        createContextWrapper : function(passfield, spanClass)
        {
            //create the wrapper and add its class
            //it has to be an inline element because we don't know its context
            var wrapper = document.createElement('span');

            if (spanClass != '') {
                wrapper.className = spanClass;
            }

            //enforce relative positioning
            // wrapper.style.position = 'relative';

            //insert the wrapper directly before the passfield
            passfield.parentNode.insertBefore(wrapper, passfield);

            //then move the passfield inside it
            wrapper.appendChild(passfield);

            //return the wrapper reference
            return wrapper;
        },


        //force a form to reset its values, so that soft-refresh does not retain them
        forceFormReset : function(textbox)
        {
            //find the parent form from this textbox reference
            //(which may not be a textbox, but that's fine, it just a reference name!)
            while(textbox)
            {
                if(/form/i.test(textbox.nodeName)) { break; }
                textbox = textbox.parentNode;
            }
            //if the reference is not a form then the textbox wasn't wrapped in one
            //so in that case we'll just have to abandon what we're doing here
            if(!/form/i.test(textbox.nodeName)) { return null; }

            //otherwise bind a load event to call the form's reset method
            //we have to defer until load time for IE or it won't work
            //because IE renders the page with empty fields
            //and then adds their values retrospectively!
            //(but in other browsers we can use DOMContentLoaded;
            // and the load listener function takes care of that split)
            this.addSpecialLoadListener(function() { textbox.reset(); });

            //return the now-form reference
            return textbox;
        },


        //copy the HTML from a password field to a plain text field,
        //we have to convert the field this way because of Internet Explorer
        //because it doesn't support setting or changing the type of an input
        convertPasswordFieldHTML : function(passfield, addedattrs)
        {
            //start the HTML for a text field
            var textfield = '<input';

            //now run through the password fields' specified attributes
            //and copy across each one into the textfield HTML
            //*except* for its name and type, and any formtools underscored attributes
            //we need to exclude the name because we'll define that separately
            //depending on the situation, and obviously the type, and formtools attributes
            //because we control them and their meaning in separate conditions too
            for(var fieldattributes = passfield.attributes,
                    j=0; j<fieldattributes.length; j++)
            {
                //we have to check .specified otherwise we'll get back every single attribute
                //that the element might possibly have! which is what IE puts in the attributes
                //collection, with default values for unspecified attributes
                if(fieldattributes[j].specified && !/^(_|type|name)/.test(fieldattributes[j].name))
                {
                    textfield += ' ' + fieldattributes[j].name + '="' + fieldattributes[j].value + '"';
                }
            }

            //now add the type of "text" to the end, plus an autocomplete attribute, and close it
            //we add autocomplete attribute for added safety, though it probably isnt necessary,
            //since browsers won't offer to remember it anywway, because the field has no name
            //this uses HTML4 empty-element syntax, but we don't need to distinguish by spec
            //because the browser's internal representations will generally be identical anyway
            textfield += ' type="text" autocomplete="off">';

            //return the finished textfield HTML
            return textfield;
        },


        //this crap is what it takes to force the caret in a textbox to stay at the end
        //I'd really rather not to do this, but it's the only way to have reliable encoding
        limitCaretPosition : function(textbox)
        {
            //create a null timer reference and start function
            var timer = null, start = function()
                {
                    //prevent multiple instances
                    if(timer == null)
                    {
                        //IE uses this range stuff
                        if(this.isIE)
                        {
                            //create an interval that continually force the position
                            //as long as the field has the focus
                            timer = window.setInterval(function()
                            {
                                //we can only force position to the end
                                //because there's no way to know whether there's a selection
                                //or just a single caret point, because the range methods
                                //we could use to determine that don't work on created fields
                                //(they generate "Invalid argument" errors)
                                var range = textbox.createTextRange(),
                                    valuelength = textbox.value.length,
                                    character = 'character';
                                range.moveEnd(character, valuelength);
                                range.moveStart(character, valuelength);
                                range.select();

                                //not so fast as to be a major CPU hog
                                //but fast enough to do the job effectively
                            }, 100);
                        }
                        //other browsers have these selection properties
                        else
                        {
                            //create an interval that continually force the position
                            //as long as the field has the focus
                            timer = window.setInterval(function()
                            {
                                //allow selection from or position at the end
                                //otherwise force position to the end
                                var valuelength = textbox.value.length;
                                if(!(textbox.selectionEnd == valuelength && textbox.selectionStart <= valuelength))
                                {
                                    textbox.selectionStart = valuelength;
                                    textbox.selectionEnd = valuelength;
                                }

                                //ditto
                            }, 100);
                        }
                    }
                },

                //and a stop function
                stop = function()
                {
                    window.clearInterval(timer);
                    timer = null;
                };

            //add events to start and stop the timer
            this.addListener(textbox, 'focus', function() { start(); });
            this.addListener(textbox, 'blur', function() { stop(); });
        },


        //add an event listener
        //this is deliberately not called "addEvent" so that we can
        //compress the name, which would otherwise also effect "addEventListener"
        addListener : function(eventnode, eventname, eventhandler)
        {
            if(typeof document.addEventListener != 'undefined')
            {
                return eventnode.addEventListener(eventname, eventhandler, false);
            }
            else if(typeof document.attachEvent != 'undefined')
            {
                return eventnode.attachEvent('on' + eventname, eventhandler);
            }
        },


        //add a special load listener, split between
        //window load for IE and DOMContentLoaded for others
        //this is only used by the force form reset method, which wants that split
        addSpecialLoadListener : function(eventhandler)
        {
            //we specifically need a browser condition here, not a feature test
            //because we know specifically what should be given to who
            //and that doesn't match general support for these constructs
            if(this.isIE)
            {
                return window.attachEvent('onload', eventhandler);
            }
            else
            {
                return document.addEventListener('DOMContentLoaded', eventhandler, false);
            }
        },


        //get an event target by sniffing for its property name
        //(assuming here that e is already a cross-model reference
        //as it is from addListener because attachEvent in IE
        //automatically provides a corresponding event argument)
        getTarget : function(e)
        {
            //just in case!
            if(!e) { return null; }

            //otherwise return the target
            return e.target ? e.target : e.srcElement;
        }

    }

