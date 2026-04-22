<?php include('includes/include.php');
include_once('helpers/DataController.php');
$modify_log = new DataController();

$video_id = $_POST['video_id'];

$ffmpeg_output = shell_exec("ffmpeg -i \"$file\" 2>&1");

if( preg_match('/.*Duration: ([0-9:]+).*/', $ffmpeg_output, $matches) ) {
    echo $matches[1];
} else {
    echo "$file failed\n";
}
$date = date("Y-m-d");

if ($video_id) {
  $update = db_query("update learning_zone set view_count = view_count+1 where type='Video' and video_id =" . $video_id);

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
//print_r($log);
$user_view_update = $modify_log->insert($log, "learning_centre_user");
//print_r($user_view_update);die;
}

$user_id = $_SESSION['user_id'];


if ($_POST['product_id']) {
  $product_id = $_POST['product_id'];
  $product_name = db_query("select title from learning_zone where id =$product_id limit 1")->fetch_array();
  // print_r($product_name['title']);
  $old = db_query("select id from learning_centre_user where video_doc_id =$product_id AND user_id = $user_id and type='Doc' order by id desc limit 1")->fetch_array();

  if($old)
  {

  }else{
  $log = [
    'title'       => htmlspecialchars($product_name['title'], ENT_QUOTES),
    'video_doc_id'=> $product_id,
    'user_id'     => $_SESSION['user_id'],
    'team_id'     => $_SESSION['team_id'],
    'date_v'     => $date,
    'time_v'     => date("H:i:s"),
    'role'        => $_SESSION['role'],
    'type'        => 'Doc',
    
  ];
  $user_view_update = $modify_log->insert($log, "learning_centre_user");
  }
  $update = db_query("update learning_zone set view_count = view_count+1 where type='Doc' and id =" . $_POST['product_id']);

}

if($_POST['delete_vid']){
 
  $delete_date = "'".date('Y-m-d H:i:s')."'";
  $delete_video = db_query("update learning_zone set status=0 and delete_date= ".$delete_date." where type='Video' and id =" . $_POST['delete_vid']);
}

if($_POST['delete_docId']){
 
  $delete_date = "'".date('Y-m-d H:i:s')."'";
  $delete_video = db_query("update learning_zone set status=0 and delete_date= ".$delete_date." where type='Doc' and id =" . $_POST['delete_docId']);
}

if($_POST['restore_id']){
 
  $restore_video = db_query("update learning_zone set status=1 where id =" . $_POST['restore_id']);
}

if (isset($_POST['view'])) {
  echo '<video width="200" controls="true" poster="" id="video">
    <source type="video/mp4" src="http://localhost/CorelDR-V2/corel_new/videos/Learn%20to%20be%20Alone%20-%20Sadhguru.mp4"></source>
</video>

<div id="status" class="incomplete">
<span>Play status: </span>
<span class="status complete">COMPLETE</span>
<span class="status incomplete">INCOMPLETE</span>
<br />
</div>
<div>
<span id="played">0</span> seconds out of 
<span id="duration"></span> seconds. (only updates when the video pauses)
</div>';
}
?>
<script>
  var video = document.getElementById("video");

  var timeStarted = -1;
  var timePlayed = 0;
  var duration = 0;
  // If video metadata is laoded get duration
  if (video.readyState > 0)
    getDuration.call(video);
  //If metadata not loaded, use event to get it
  else {
    video.addEventListener('loadedmetadata', getDuration);
  }
  // remember time user started the video
  function videoStartedPlaying() {
    timeStarted = new Date().getTime() / 1000;
  }

  function videoStoppedPlaying(event) {
    // Start time less then zero means stop event was fired vidout start event
    if (timeStarted > 0) {
      var playedFor = new Date().getTime() / 1000 - timeStarted;
      timeStarted = -1;
      // add the new number of seconds played
      timePlayed += playedFor;
    }
    document.getElementById("played").innerHTML = Math.round(timePlayed) + "";
    // Count as complete only if end of video was reached
    if (timePlayed >= duration && event.type == "ended") {
      document.getElementById("status").className = "complete";
    }
  };


  function getDuration() {
    duration = video.duration;
    document.getElementById("duration").appendChild(new Text(Math.round(duration) + ""));
    console.log("Duration: ", duration);
  }

  video.addEventListener("play", videoStartedPlaying);
  video.addEventListener("playing", videoStartedPlaying);

  video.addEventListener("ended", videoStoppedPlaying);
  video.addEventListener("pause", videoStoppedPlaying);
</script>