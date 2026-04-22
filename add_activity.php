<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);

?>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #86c9d0;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}


/* Speetch to text */

#myModalAudio {
    background: rgba(0, 0, 0, .32);
    backdrop-filter: blur(5px);
    z-index: 9999999;
}

.mic-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #86c9d0;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
  }
  .mic-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
  }
  .mic-btn.active {
    background: #dc3545; /* red when active */
    animation: pulse 1.5s infinite;
  }
  .mic-btn img {
    width: 28PX;
    height: 28PX;
    filter: brightness(0) invert(1);
  }
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(220,53,69, 0.5); }
    70% { box-shadow: 0 0 0 20px rgba(220,53,69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220,53,69, 0); }
  }

.textResult {
  display: block;
  width: 100%;
  padding: 1rem;
  font-size: .8125rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: .25rem;
  min-height: 150px;
}


</style>

<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity Call for <?= $_POST['company_name'] ?>
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query);

    $association_query = db_query("select * from activity_log left join tbl_lead_product tp on activity_log.pid=tp.lead_id where tp.product_type_id in (1,2) and activity_log.pid=" . $_POST['pid'] . " and activity_log.call_subject='Fresh Call'");

    $iss_lead = getSingleresult("select iss from orders left join tbl_lead_product tp on orders.id=tp.lead_id where tp.product_type_id in (1,2) and orders.id=" . $_POST['pid']);
    ?>
    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20" onsubmit="disableButton(this.querySelector('button'))">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Call Subject<span class="text-danger">*</span></label>
              <select required name="call_subject" class="form-control">
                <option value="">Select Call Subject</option>
                <?php

                if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'REVIEWER') {

                  $call_query = db_query("select * from call_subject where 1 order by subject");
                  while ($call_subject = db_fetch_array($call_query)) {  ?>

                    <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                    <?php }

                } else {

                  if ($row_data['role'] == 'TC' || ($_SESSION['user_type'] == 'CLR' &&$_SESSION['role'] == 'ISS')) {
                    if (mysqli_num_rows($association_query) > 0) {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject!='Fresh Call' order by subject");
                    } else if ($iss_lead == 1) {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject!='Fresh Call' order by subject");
                    } else if ($_SESSION['user_type'] == 'CLR' && $iss_lead != 1) {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject!='Fresh Call' order by subject");
                    } else {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' order by subject");
                    }

                    while ($call_subject = db_fetch_array($call_query)) {  ?>
                      <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                    <?php }
                  } else {
                    if (mysqli_num_rows($association_query) > 0) {

                      $call_query = db_query("select * from call_subject where 1 and subject!='Fresh Call' order by subject");
                    } else if ($iss_lead == 1) {
                      $call_query = db_query("select * from call_subject where subject!='Fresh Call' order by subject");
                    } else if ($_SESSION['user_type'] == 'CLR' && $iss_lead != 1) {
                      $call_query = db_query("select * from call_subject where subject!='Fresh Call' order by subject");
                    } else {
                      $call_query = db_query("select * from call_subject where 1 order by subject");
                    }

                    while ($call_subject = db_fetch_array($call_query)) {  ?>

                      <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                <?php }
                  }
                } ?>
              </select>
            </div>
          </div>

         <div class="col-md-12">
              <div class="form-group" style="position: relative;"> <!-- position relative for overlay -->
                <label for="example-text-input">Visit/Profiling Remarks <span class="text-danger">*</span></label>

                <textarea id="result" required name="remarks" class="form-control" placeholder="" oninput="this.value=this.value.trimStart()"></textarea>

                <!-- Loader Over Textarea -->
                <div id="grammar-loader" style="
                  display: none;
                  position: absolute;
                  top: 0; left: 0;
                  width: 100%; height: 100%;
                  background: rgba(255, 255, 255, 0.6);
                  align-items: center;
                  justify-content: center;
                  z-index: 10;
                  pointer-events: none;">
                  <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." style="width: 40px; height: 40px;">
                </div>

              </div>
             </div>




        </div>
        


        <div class="row align-items-center">
          <div class="col-12">
            <div class="form-group mb-0">
              <label class="mb-0">Grammify</label>
              <label class="switch mb-0">
                <input type="checkbox" name="reminder" checked value="1" id="grammarCheckToggle">
                <span class="slider round"></span>
              </label>
            </div>
          </div>
           <!-- Reminder Toggle -->
          <div class="col">
            <div class="form-group mb-0">
              <label class="mb-0">Reminder</label>
              <label class="switch mb-0">
                <input type="checkbox" name="reminder" value="1" id="reminderToggle" onchange="toggleReminderFields()">
                <span class="slider round"></span>
              </label>
            </div>
          </div>
          <div class="col-auto">
            <!-- <span class="cursor-pointer" data-toggle="modal" data-target="#myModalAudio">
              <img src="images/mic.svg" alt="">
            </span> -->
            
            <!-- startBtn -->
            <div class="mic-btn" id="start-btn">
              <img src="images/mic.svg" alt="Mic">
            </div>
          </div>

          
          
          <div class="col-md-12 mt-3">            
            <!-- <div class="form-group text-center">
              <label>Grammar Check</label>
              <label class="switch">
                <input type="checkbox" name="reminder" value="1" id="grammarCheckToggle">
                <span class="slider round"></span>
              </label>
            </div> -->
            <!-- <div id="result" class="textResult">Your speech will appear here...</div> -->
          </div>
        </div>
        <!-- Date + Time Fields -->
        <div class="row" id="reminderFields" style="display: none;">

          <div class="col-md-6">
            <div class="form-group">
              <label for="reminder_date">Date <span class="text-danger">*</span></label>
              <input type="date" name="reminder_date" id="reminder_date" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="reminder_time">Time <span class="text-danger">*</span></label>
              <input type="time" name="reminder_time" id="reminder_time" class="form-control">
            </div>
          </div>
          
        </div>


          <?php $status = db_query("select status from orders where id='" . $_POST['pid'] . "'");
          $status_arr = db_fetch_array($status);

          $plan_of_action = getSingleresult("select action_plan from activity_log where pid='" . $_POST['pid'] . "' order by id desc limit 1");
          //print_r($plan_of_action);

          if ($status_arr['status'] == 'Approved') { ?>
            
          <?php } ?>

        <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

        <div class="mt-3 text-center">
          <input type="submit" name="save_activity" value="Save" class="btn btn-primary" />
          <!-- <button type="submit" class="btn btn-primary" name="save">Save </button> -->
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

        </div>
      </form>
    </div>

  </div>
</div>

<script>
        function disableButton(button) {
          const saveButton = document.querySelector('input[name="save_activity"]');
            if (saveButton) {
                saveButton.disabled = true; // Disable the button
                saveButton.value = "Processing..."; // Optional: Change button text
            }
        }
</script>

<!-- <script>
  function toggleReminderFields() {
    var checkbox = document.getElementById('reminderToggle');
    var dateField = document.getElementById('visit_date');
    var timeField = document.getElementById('visit_time');
    var container = document.getElementById('reminderFields');
    var reminder_date = document.getElementById('reminder_date');
    var reminder_time = document.getElementById('reminder_time');
    

    if (checkbox.checked) {
      console.log('Checked');
      container.style.display = 'flex';
      dateField.setAttribute('required', 'required');
      timeField.setAttribute('required', 'required');
       console.log('Checked');
      reminder_time.setAttribute('required', 'required');
      reminder_date.setAttribute('required', 'required');
       console.log('Checked');
    } else {
      container.style.display = 'none';
      dateField.removeAttribute('required');
      timeField.removeAttribute('required');
      reminder_time.setAttribute('required');
      reminder_date.setAttribute('required');
    }
  }
</script> -->
<script>
function toggleReminderFields() {
    var checkbox = $('#reminderToggle');
    var dateField = $('#visit_date');
    var timeField = $('#visit_time');
    var container = $('#reminderFields');
    var reminder_date = $('#reminder_date');
    var reminder_time = $('#reminder_time');

    if (checkbox.is(':checked')) {
        // console.log('Checked');
        container.css('display', 'flex');

        dateField.prop('required', true);
        timeField.prop('required', true);
        reminder_date.prop('required', true);
        reminder_time.prop('required', true);
    } else {
        container.hide();

        dateField.prop('required', false);
        timeField.prop('required', false);
        reminder_date.prop('required', false);
        reminder_time.prop('required', false);
    }
}
</script>


<!-- <script>
  const result = document.getElementById("result");
  const startBtn = document.getElementById("startBtn");

  // Check browser support
  window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

  if ('SpeechRecognition' in window) {
    const recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = "en-US";

    let isListening = false;

    startBtn.addEventListener("click", () => {
      if (!isListening) {
        recognition.start();
        startBtn.classList.add("active");
        isListening = true;
      } else {
        recognition.stop();
        startBtn.classList.remove("active");
        isListening = false;
      }
    });

    recognition.onresult = (event) => {
      let transcript = "";
      for (let i = event.resultIndex; i < event.results.length; i++) {
        transcript += event.results[i][0].transcript;
      }
      result.textContent = transcript;
    };

    recognition.onerror = (event) => {
      console.error("Speech recognition error", event.error);
    };

    recognition.onend = () => {
      startBtn.classList.remove("active");
      isListening = false;
    };
  } else {
    alert("Sorry, your browser does not support Speech Recognition.");
    startBtn.style.pointerEvents = "none";
  }
</script> -->
<script>
const result = document.getElementById("result");
const startBtn = document.getElementById("start-btn");
const grammarToggle = document.getElementById("grammarCheckToggle");

window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

if ('SpeechRecognition' in window) {
  const recognition = new SpeechRecognition();
  recognition.continuous = true;
  recognition.interimResults = true;
  recognition.lang = "en-US";

  let isListening = false;
  let finalTranscript = "";

  // Start/Stop mic
  startBtn.addEventListener("click", () => {
    if (!isListening) {
      recognition.start();
      startBtn.classList.add("active");
      isListening = true;
    } else {
      recognition.stop();
      startBtn.classList.remove("active");
      isListening = false;
    }
  });

recognition.onresult = (event) => {
  let interimTranscript = "";
  for (let i = event.resultIndex; i < event.results.length; i++) {
    const transcript = event.results[i][0].transcript;
    if (event.results[i].isFinal) {
      finalTranscript += transcript + " ";
    } else {
      interimTranscript += transcript;
    }
  }
  result.value = finalTranscript + interimTranscript; // Shows "live" speech in textarea
};

  recognition.onerror = (event) => {
    console.error("Speech recognition error:", event.error);
  };

  recognition.onend = () => {
    startBtn.classList.remove("active");
    isListening = false;

    // Optional grammar correction
    if (grammarToggle.checked && finalTranscript.trim()) {
      correctGrammar(finalTranscript.trim());
    }
  };

  // Grammar correction
  function correctGrammar(text) {
    const loader = document.getElementById("grammar-loader");
    loader.style.display = "flex";

    fetch("http://127.0.0.1:8000/text_ai/correct/", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ text })
    })
      .then(response => response.json())
      .then(data => {
        const corrected = data.corrected_text || text;
        result.value = corrected;
        loader.style.display = "none";
      })
      .catch(error => {
        console.error("Grammar correction failed:", error);
        loader.style.display = "none";
      });
  }
} else {
  alert("Your browser does not support the Speech Recognition API.");
  startBtn.style.pointerEvents = "none";
}
</script>
<script>
    // Prevent past date
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("reminder_date").setAttribute("min", today);

    // Prevent past time if date is today
    document.getElementById("reminder_date").addEventListener("change", function () {
        let selectedDate = this.value;
        let timeInput = document.getElementById("reminder_time");

        let now = new Date();
        let currentTime = now.toTimeString().slice(0,5);
        let today = new Date().toISOString().split("T")[0];

        if (selectedDate === today) {
            timeInput.setAttribute("min", currentTime);
        } else {
            timeInput.removeAttribute("min");
        }
    });
</script>
