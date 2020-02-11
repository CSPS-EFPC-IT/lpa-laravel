import VeeValidate from 'vee-validate';

const email = {
  validate(value, args) {
    const EMAILREG = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return VeeValidate.Rules.email(value) || EMAILREG.test(value);
  }
};

// extend the default email rule validation
VeeValidate.Validator.extend('email', email);
