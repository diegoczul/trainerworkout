{{ Form::open(array('url' => '/widgets/videoWord/addEdit/')); }}
<div class="add_testimonial">
<h6>Video and Words </h6>

<div id="" >
 <textarea id="video" name="video" class="descriptionObjectives border-radius" placeholder="Video Url">{{ $user->videoLink }}</textarea>
</div>
<div id="">
 <textarea id="word" name="word" class="descriptionObjectives border-radius" placeholder="Words">{{ $user->word }}</textarea>
	   <input style="margin-top:20px;" type="submit" class="bluebtn ajaxSave" value="Save" widget="w_video_word_full">
	</div> 


	</div>
{{Form::close() }}

<script>




  $(document).ready(function(){

  CKEDITOR.replace("word");

  
	CKEDITOR.config.toolbar = [
             ['Styles','Format','Font','FontSize'],['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
             ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']] ;

  CKEDITOR.instances["word"].setData("{{ trim(preg_replace('/\s\s+/', ' ', $user->word)) }}");



  });

 
  	
</script>
