$(document).ready(function(){
    if($('input[type=radio][name=user_type_id]') !== undefined)
    {
        if($('#userTypeId-1').is(':checked')) 
        {
            $("#jobRoleField").css('display','none');
            $("#expField").css('display','none');
            $("#jobTitleField").css('display','none');
        }
        $('input[type=radio][name=user_type_id]').change(function() {
            if (this.value == '1') {
                $("#jobRoleField").css('display','none');
                $("#expField").css('display','none');
                $("#jobTitleField").css('display','none');
            }
            else if (this.value == '2') { 
                $("#jobRoleField").css('display','');
                $("#expField").css('display','');
                $("#jobTitleField").css('display','');
            }
        });
    }
    clonableWidget('preferred_job_title','preferred_titles_container', 'Ex. Head of Human Resources');
});

function clonableWidget(fieldName, fieldContainer, placeholder)
{
    var max_fields = 10;
    var wrapper = $("#"+fieldContainer);
    var add_button = $(".add_"+fieldName+"_field");
    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper).append('<div><input type="text" placeholder="'+placeholder+'" class="my-2 form-control" name="'+fieldName+'[]"/><a href="javascript:;" class="delete_'+fieldName+'"><i class="fas fa-window-close"></i></a></div>'); //add input box
        } else {
            alert('You Reached the limits')
        }
    });

    $(wrapper).on("click", ".delete_"+fieldName, function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    })
}

function checkToggler() {
    if($('#toggler_input').prop('checked')) {
            $('#toggle_el').css('display','none');
      } else {
            $('#toggle_el').css('display','block');
      }
}