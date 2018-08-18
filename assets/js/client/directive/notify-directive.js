(function(appMonami) {
    'use strict';

    appMonami.directive("notifyMessage",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let message = attrs.message;
                let delay = attrs.delay ? parseInt(attrs.delay) : 3000;

                $.notify({message: message},
                    {
                        delay: delay,
                        placement: {
                            from: "top",
                            align: "center"
                        },
                        type: "success",
                    });
            }
        };
    }]);

})(window.appMonami);
