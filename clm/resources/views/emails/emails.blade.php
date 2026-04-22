<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />
        <style>
            body {
                font-family: "Montserrat", sans-serif;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%; margin: 0 auto;">
            <table style="width: 60%; margin: 0 auto;">
                <tr>
                    <td style="text-align: left;"><img src="https://dr.ict360.com/images/corel.png" height="40" alt="Logo"/></td>
                    <td style="text-align: right;"></td>

                   
                </tr>
            </table>
            <table style="width: 100%; margin: 0 auto; background-image: url('images/bg.jpg'); background-position-x: center; background-position-y: center;">
                <tr>
                    <td>
                        
                    </td>
                </tr>
            </table>
            
            <div class="content">
                <table style="width: 60%; margin: 30px auto 20px auto; font-family: 'Lato', sans-serif; font-size: 13px; ">
                    
                
                    <tr>
                        <th colspan="2" style=" color: #000; text-align: left; padding: 5px 10px; font-size: 15px;">
                            <h4>Hi,</h4>
                            </th>
                    </tr>

                    <tr>
                        <th colspan="2" style=" color: #000; text-align: left; padding: 5px 10px; font-size: 15px;">
                        <h4>Below task has been assigned to you :</h4>
                        </th>
                    </tr>
                   
                </table>
                
              <table style="width: 60%; margin: 0 auto 20px auto; border: 1px solid black; border-collapse: collapse; font-family: 'Lato', sans-serif; font-size: 13px; line-height: 22px;">
                  
                  <tbody>
                     
                     <tr>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Task generated date: </td>                   
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$g_date}}</td>
                    </tr>
                    <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Task due date: </td>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$d_date}}</td>
                    </tr>
                    <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Task subject:</td>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$t_subject}}</td>
                    </tr>
                               
                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                         <td  style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">School name:  </td>
                         <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$s_name}}</td>
                      </tr>
                      

                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Address: </td>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$address}}</td>
                        
                      </tr>
                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">City: </td>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$city}}</td>
                      </tr>

                      
                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">State: </td>
                        <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$state}}</td>
                      </tr>

                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">

                           <td  style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Contact Number</td>
                           <td  style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$c_number}} </td>
                      </tr>
                      
                      <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                          <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Email id:  </td>
                          <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$email}} </td>
                         
                        </tr>

                        {{-- <tr style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">
                            <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Message:  </td>
                            <td style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">{{$msg}} </td>
                           
                          </tr> --}}
                       
                          <tr>
                            <td colspan="2"  style="padding: 5px 10px; border: 1px solid black; border-collapse: collapse; font-size: 13px;">Link to update your activity update: <span><a target="_blank" href="{{$link}}">open the link</a></span></td>
                          </tr>
                  </tbody>
              </table>
             
            </div>

            
        </table>
    </body>
</html>
