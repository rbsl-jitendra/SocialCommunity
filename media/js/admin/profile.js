window.addEvent('domready', function(){ 
	// Validation script
    Joomla.submitbutton = function(task){
        if (task == 'profile.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    };
})