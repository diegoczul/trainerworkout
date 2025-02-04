{{ Form::open(array('url' => '/widgets/biography/addEdit/')); }}          
<div class="add_testimonial" style="margin-bottom:20px;">
<h6>Bio, Certifications and Experience </h6>
<form id="testimonial">

<div id="" >
<h2 style="margin-top:15px">Biography</h2>
 <textarea id="biography" name="biography" class="descriptionObjectives border-radius" placeholder="Biography">{{{ $user->biography }}}</textarea>
 <h2 style="margin-top:15px">Certifications</h2>
  <textarea id="Certifications" name="certifications" class="descriptionObjectives border-radius" placeholder="Certifications">{{ $user->certifications }}</textarea>
 <h2 style="margin-top:15px">Past Experience</h2> 
    <textarea id="past_experience" name="past_experience" class="descriptionObjectives border-radius" placeholder="Experience">{{ $user->past_experience }}</textarea>
 
                           <input style="margin-top:20px;" type="submit" class="bluebtn ajaxSave" value="Save" widget="w_biography_full">
                        </div> 
                    </div>
{{ Form::close() }}

<script>




  $(document).ready(function(){

  CKEDITOR.replace("biography");
  CKEDITOR.replace("Certifications");
  CKEDITOR.replace("past_experience");
  
	CKEDITOR.config.toolbar = [
             ['Styles','Format','Font','FontSize'],['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
             ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']] ;

  CKEDITOR.instances["biography"].setData("{{ trim(preg_replace('/\s\s+/', ' ', $user->biography)) }}");
  CKEDITOR.instances["Certifications"].setData("{{ trim(preg_replace('/\s\s+/', ' ', $user->certifications)) }}");
	CKEDITOR.instances["past_experience"].setData("{{ trim(preg_replace('/\s\s+/', ' ', $user->past_experience)) }}");


  });

 
  	
</script>
        
			