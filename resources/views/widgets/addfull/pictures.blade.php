
{{ Form::open(array('url' => '/widgets/pictures/addEdit/', "files"=>true)); }}
<div class="profielpictures clearfix">
                	<div class="profiledates"><input type="text" name="recordDate" id="calendarPictures" class="datepicker inputbox-small" placeholder="Date" value="{{ date("Y-m-d") }}" /></div>
                    <div class="profileimages">
                    	<fieldset>

                            <label>Front Side</label>

                            <div class="Browsefile clearfix">
                                <input type="file" class="hidden" id="front" name="front"/>
                                <input class="bluebtn alignright" type="button" name="btn-upload" value="Add Picture" onclick="uploadImage($(this))">
                                <p class="path"></p> 
                            </div>

                        </fieldset>
                    </div>
                    <div class="profileimages">
                    	<fieldset>

                            <label>Back Side</label>

                            <div class="Browsefile clearfix">
                                <input type="file" class="hidden"  id="back" name="back"/>
                                <input class="bluebtn alignright" type="button" name="btn-upload" value="Add Picture" onclick="uploadImage($(this))"> 
                                <p class="path"></p> 
                            </div>

                        </fieldset>
                    </div>
                    <div class="profileimages">
                    	<fieldset>

                            <label>Left Side</label>

                            <div class="Browsefile clearfix">
                                <input type="file" class="hidden"  id="left" name="left"/>
                                <input class="bluebtn alignright" type="button" name="btn-upload" value="Add Picture" onclick="uploadImage($(this))"> 
                                <p class="path"></p> 
                            </div>

                        </fieldset>
                    </div>
                    <div class="profileimages">
                    	<fieldset>

                            <label>Right Side</label>

                            <div class="Browsefile clearfix">
                                <input type="file" class="hidden"  id="right" name="right"/>
                                <input class="bluebtn alignright" type="button" name="btn-upload" value="Add Picture" onclick="uploadImage($(this))"> 
                                <p class="path"></p> 
                            </div>

                        </fieldset>
                    </div>
                    
                </div>
                <div class="clearfix"></div>
                <div style="width:100%" class="alignright">
                 <input class="bluebtn alignright ajaxSave" type="submit" name="btn-upload" value="Upload All" widget="w_pictures_full" > 
                 </div> 
{{ Form::close() }}

<script>

function uploadImage(obj){
    $(obj).prev("input").on('change', function(e) {
       $(obj).next(".path").text($(obj).prev("input").val());
    });
    $(obj).prev("input").click();
}
</script>