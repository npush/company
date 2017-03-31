/**
 * Created by nick on 8/3/16.
 */


    var ajaxlogin = {
        firstname: '',
        telephone: '',
        email: '',
        password: '',
        passwordConfirm: '',
        licence: '',
        controllerUrl: '',
        init: function(redirection, profileUrl, autoShowUp, controllerUrl){
            _this = this;
            this.controllerUrl = controllerUrl;
            // Click login
            jQuery('#login button').on('click', function(){
                _this.collectField('#login');
                _this.ajaxLogin();
            });
            //click register
            jQuery('#user_registration button').on('click', function(){
                _this.collectField('#user_registration');
                _this.collectField('#user_registration');
                _this.ajaxRegistration();
            });
        },
        collectField: function(context){
            _this = this;
            jQuery(context + ' input').each(function(){
                var _name = jQuery(this).attr('name');
                _this[_name] = jQuery(this).val();
                if(_name === 'licence')  _this[_name] = 'ok';
                console.log(jQuery(this));
            });
        },
        ajaxRegistration: function() {
            _this= this;
            jQuery.ajax({
                url: this.controllerUrl,
                type: 'POST',
                data: {
                    ajax: 'register',
                    firstname: this.firstname,
                    telephone: this.telephone,
                    email: this.email,
                    password: this.password,
                    passwordsecond: this.passwordConfirm,
                    licence: this.licence
                },
                dataType: "html"
            }).done(
                function(msg){
                    // If there is error
                    if (msg != 'success'){
                        //error
                        // .....
                        console.log(msg);
                    }else{
                        // If everything are OK
                        // Redirect
                        if (_this.redirection == '1') {
                            window.location = _this.profileUrl;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            ).fail(function(){
                    alert("error");
                });
        },
        ajaxLogin: function(){
            _this= this;
            jQuery.ajax({
                url: this.controllerUrl,
                type: 'POST',
                data: {
                    ajax : 'login',
                    email : this.email,
                    password : this.password
                },
                dataType: "html"
            }).done(function(msg){
                // If there is error
                if (msg != 'success'){
                    //error
                    // .....
                    console.log('Error' + msg);
                }else{
                    // If everything are OK
                    // Redirect
                    if (_this.redirection == '1') {
                        window.location = _this.profileUrl;
                    } else {
                        window.location.reload();
                    }
                }
            }).fail(function(){
                alert("error");
            });
        }
    };

