// DON'T TOUCH ANYTHING BELOW!!!!

//Device list Object
var device = {
    branding : "iPhone7-portrait",
    clientFiles : "iPad-landscape",
    notifications : "iPad-portrait",
    tracking : "desktop",
    exercises: "none",
    builder: "iPhone7-portrait",
    share: "iPad-portrait",
    print: "none",
    gbranding: "iPhone7-portrait",
    acitivtyLog: "iPad-landscape",
    // progress: "iPhone7-portrait",
    workouts: "iPad-portrait",
    gexercises: "none",
    gbuilder:"iPhone7-portrait",
    gshare: "iPad-portrait",
    gprint: "none",
    employee: "iPad-landscape",
    global: "iPhone7-portrait",
    collaboration: "iPad-portrait"
};

//Function that recognize the next tab to be selcted
//cliker for the next tab to be clicked
function switchTab(tab) {
    //get currently-selected tab
    var selectedTab = tab.filter('.selected');

    //click either next tab, if exists, else first one
    var nextTab = selectedTab.index() < tab.length-1 ? selectedTab.next() : tab.first();
    nextTab.click();
}



//Mobile tabs
$(".selectableTab").change(function($this) {
    //Get the className of the tab selected
    var tab = $(this).val()

   //mimic the click as if it the button was clicked. 
   $("." + tab).click();
});



//The magic combined
//switch the tab open
$(".tab").click(function($this) {
    //Get the className of the tab selected
    var classes = this.classList;
    var tab = classes[1];

    //get in which setion we are
    var $id = $(this).closest(".parentHolderTab").attr('id');

   //remove the selction
   $(this).closest(".tabsContainer").find(".tab").removeClass("selected");

   //apply the selection
   $(this).addClass("selected");

    //pick the right image
   $(this).closest(".mainSectionContent").find(".screen").addClass("hideMe");
   $(this).closest(".mainSectionContent").find("#" + tab).removeClass("hideMe");

   //show the right message 
   $(this).closest(".mainSectionContent").find(".message p").addClass("hideMe");
   $(this).closest(".mainSectionContent").find("#" + tab + "Message").removeClass("hideMe");

   //get the right device back on
   $(this).closest(".mainSectionContent").find(".device").attr('class', 'device').addClass(device[tab]);


   if ($id == "secondary"){

       //clear interval and reset if in second
       clearInterval(secondTimer);
       secondTimer = setInterval(switchSecondTab, sec_time);

   } else if ($id == "third"){

        //clear interval and reset if in second
       clearInterval(thirdTimer);
       thirdTimer = setInterval(switchThirdTab, third_time);

   } else if ($id == "forth") {

       //clear interval and reset if in second
       clearInterval(forthTimer);
       forthTimer = setInterval(switchForthTab, forth_time);

   } else {

    console.log("This block is not linked in the tabSwitcher.js file");

   }

   // Apply the selection to the mobile one
   $(this).closest(".parentHolderTab").find(".selectableTabContainer").find('option[value=' + tab + ']').prop('selected', true)
});



