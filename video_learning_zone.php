<?php include('includes/include.php');
include_once('helpers/DataController.php');
$modify_log = new DataController();

$video_id = $_POST['video_id'];
$date = date("Y-m-d");
$user_id = $_SESSION['user_id'];

if ($video_id) {
  $update = db_query("update learning_zone set view_count = view_count+1 where type='Video' and video_id =" . $video_id);
    $old_duration = db_query("select id,duration from learning_centre_user where video_doc_id =$video_id AND user_id = $user_id and type='Video' order by id desc limit 1")->fetch_array();
  if($old_duration){

    // die;
  }else{

    $log = [
      'title'       => htmlspecialchars($_POST['title'], ENT_QUOTES),
      'video_doc_id'=> $video_id,
      'user_id'     => $_SESSION['user_id'],
      'team_id'     => $_SESSION['team_id'],
      'date_v'     => $date,
      'time_v'     => date("H:i:s"),
      'role'        => $_SESSION['role'],
      'type'        => 'Video',
    ];
    $user_view_update = $modify_log->insert($log, "learning_centre_user");
  }
}

$select = db_query("select vdo_address from training_videos where id =$video_id")->fetch_array();
$vdo_address = $select['vdo_address']; 

?>
<style>
video::-webkit-media-controls-timeline {
    display: none;
}
</style>
<input type="hidden" name="duration_val" id="duration_val" value="<?php echo $old_duration['duration']  ?>">
<input type="hidden" name="check_video_id" id="check_video_id" value="<?php echo $video_id  ?>">
<!-- <button onclick="setCurTime(0)" type="button">Start Over</button>
<button onclick="setCurTimeB()" type="button">5s Back</button>
<button onclick="setCurTimeF()" type="button">5s Forward</button> -->

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="">Modal title</h5> -->
        <button type="button" class="close" onclick="closeModel()" aria-label="Close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
      <video width="100%" controls="controls autoplay" id="myVideo">

<source src="<?php echo $vdo_address; ?>" >
</video>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div> -->
    </div>
  </div>

<script>
const video = document.querySelector('video');
var duration_val=document.getElementById("duration_val").value;  
var vid = document.getElementById("myVideo");

function getCurTime() { 
  return(vid.currentTime);
} 

if(duration_val > 0)
{
    setCurTime((duration_val-1))
}

function setCurTime(duration_val) { 
  
  vid.currentTime=duration_val;
} 

function setCurTimeB() {
  if(vid.currentTime > 5){
  vid.currentTime=vid.currentTime-5;
  }
  else{
  vid.currentTime=0;
  }
} 

function setCurTimeF() { 
  vid.currentTime=vid.currentTime+5;
} 

function closeModel()
{
  vid.pause();
  endVideoUpdate(vid.currentTime);
  // location.reload();
 }

// document.getElementById('myVideo').play();
// document.getElementById("myVideo").controls = false; 

video.addEventListener('pause', (event) => {
        // document.getElementById("myVideo").controls = true;
    endVideoUpdate(vid.currentTime);
});

video.addEventListener('ended', (event) => {
    
    // document.getElementById("myVideo").controls = true;
    endVideoUpdate(vid.currentTime);
   
});


video.addEventListener('playing', (event) => {
  console.log('Video is no longer paused');
  getCurTime();

});




           function endVideoUpdate(duration){
            var video_id=document.getElementById("check_video_id").value;  
            $.ajax({
                    type: 'POST',
                    url: 'update_video_duration.php',
                    data: {
                        video_id: video_id,
                        duration:duration
                    },
                    success: function(response) {
                    
                    }
                });
           }

</script>
  