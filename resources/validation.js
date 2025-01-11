const validation = (function(window, document) {
    'use strict';
    
    class FormElement {
        constructor(el, messages) {
            this.messages = messages;

            el.addEventListener('input', (e) => {
                this.handleMessage(e);
            });
            
            el.addEventListener('invalid', (e) => {
                this.handleMessage(e);
            });
        }
        getMessage(validity) {
            const attribute = validity.valueMissing ? 'required' : validity.typeMismatch ? 'mismatch' : validity.patternMismatch ? 'pattern' : validity.tooShort ? 'minlength' : validity.tooLong ? 'maxlength' : validity.rangeUnderflow ? 'min' : validity.rangeOverflow ? 'max' : validity.stepMismatch ? 'step' : validity.customError ? 'custom' : '';
            
            return (typeof this.messages[attribute] === 'undefined') ? '' : this.messages[attribute];
        }
        handleMessage(event) {
            const message = this.getMessage(event.target.validity);
            
            event.target.setCustomValidity('');
            
            if (message === '') {
                return;
            }

            event.target.setCustomValidity(message);
        }
    }

    const validation = {
        register: function(dataMessages = 'data-validation-messages') {
            document.querySelectorAll('['+dataMessages+']').forEach(el => {
                if (!el.willValidate) {
                    return;
                }
                
                const messages = JSON.parse(el.getAttribute(dataMessages));
                el.removeAttribute(dataMessages);
                new FormElement(el, messages);
            });
        }
    };
    
    document.addEventListener('DOMContentLoaded', (e) => {
        validation.register();
    });
    
    return validation;
    
})(window, document);

export default validation;