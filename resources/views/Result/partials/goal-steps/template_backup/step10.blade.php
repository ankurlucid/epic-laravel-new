<div class="step data-step" data-step="10">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="9"><span>9.</span></div>

                        <label class="steps-head">Is this goal associated with a <strong>life event or special occasion</strong>? * </label>

                      </div>

                      <div class="tooltip-sign mb-10">
                          <a href="javascript:void(0)" class="goal-step" 
                           data-message="Do you have any special occasion coming up? This may be anything from:
                           <br/><br/>
                           • Turning a certain age and changing your perspective on life and order of priorities? <br/>
                           • Needing to perform at a certain event? <br/>
                           • Getting married?<br/>
                           • Feeling good and confident on holiday?
                           <br/><br/>
                           Do you need to achieve this goal in time for the event? (Wedding, holiday, or summer)
                           <br/><br/>
                           Is there a larger life event or stage of life that you may be reaching that has shifted your mindset and helped you decide to act? (Closing in on 50's or possibly of children or grandchildren)"
                           data-message1="Do you have any special occasion coming up?<br/>
                           Do you need to achieve this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> in time for the event? (Wedding)<br/>
                           Is there a larger life event or stage of life that you may be reaching that has shifted your midst and helped you decide to act? (Closing in on 50's or Possibility of children or grandchildren)"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                     <div class="form-group template-relevant-goal-event">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal_event" name="gb_relevant_goal_event" ng-model="gb_relevant_goal_event" ng-init="gb_relevant_goal_event='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal_event: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal_event:null}}</textarea>
                      </div>
                     </div>
                  </div>

<script type="text/javascript">
  $(document).ready(function() {
    templateLoad();
    loadFormData();
  });

  function loadFormData(){
     var current_step = $('.step').data('step');
     $.ajax({
         url: public_url + 'goal-buddy/load-form-data',
         type: "POST",
         data: {'current_step': current_step},
         success: function (data) {
            console.log(data);
            if(data.data && data.data.gb_relevant_goal_event != undefined ){
              $("input[type=radio][value='"+data.data.gb_relevant_goal_event+"']").attr("checked",true);
            }
            if(data.data && data.data.gb_relevant_goal_event_other != undefined && data.data.gb_relevant_goal_event_other != ""){
               $('#gb_relevant_goal_event_other').removeClass('hidden');
               $('#gb_relevant_goal_event_other').attr('required',true);
              //  $('#gb_relevant_goal_event_other').attr('placeholder','Describe your achievement Here...');
               $('#gb_relevant_goal_event_other').text(data.data.gb_relevant_goal_event_other);
            }
            
          }
     });
  }
  $(document).on('change',"input[name=gb_relevant_goal_event]",function(){
      var other = $('input[name=gb_relevant_goal_event][value=Other]').prop('checked');
      if(other == true){
         $('#gb_relevant_goal_event_other').removeClass('hidden');
         $('#gb_relevant_goal_event_other').attr('required',true);
        //  $('#gb_relevant_goal_event_other').attr('placeholder','Input Your Specific Goal Here...');
      }else{
         $('#gb_relevant_goal_event_other').addClass('hidden');
         $('#gb_relevant_goal_event_other').attr('required',false);
         $("#gb_relevant_goal_event_other-error").html('');
         $('#gb_relevant_goal_event_other').val('');
      }
   })
  function templateLoad(){
      /*  */
      var data = JSON.parse(sessionStorage.getItem("templateData"));

      var gb_relevant_goal_event = data.goal_template.gb_relevant_goal_event.split(",");
      //var fetch_data = data.fetch_data;
      $(".template-relevant-goal-event").html("");
      var radio_val = "";
      var html5 = "";
      var relevant_event_other = null;
      for (var i = 0; i < gb_relevant_goal_event.length; i++) {
        // if (fetch_data != null) {
        //   radio_val =
        //     fetch_data.gb_relevant_goal_event == gb_relevant_goal_event[i]
        //       ? "checked"
        //       : "";
        //   relevant_event_other = fetch_data.gb_relevant_goal_event_other;
        // }
        if (gb_relevant_goal_event[i] == "Other") {
          var other5 = "gb_relevant_goal_event_other";
          if (relevant_event_other != null) {
            html5 =
              '<textarea rows="7" class="form-control" id="gb_relevant_goal_event_other" name="gb_relevant_goal_event_other">' +
              relevant_event_other +
              "</textarea>";
          } else {
            html5 =
              '<textarea rows="7" class="form-control hidden" id="gb_relevant_goal_event_other" name="gb_relevant_goal_event_other"></textarea>';
          }
        }
        $(".template-relevant-goal-event").append(
          '<div class="form-group">\
                              <label class="container_radio version_2">' +
            gb_relevant_goal_event[i] +
            '\
                              <input type="radio" class="' +
            other5 +
            '" name="gb_relevant_goal_event" required value="' +
            gb_relevant_goal_event[i] +
            '" ' +
            radio_val +
            '>\
                              <span class="checkmark"></span>\
                              ' +
            html5 +
            "\
                              </label>\
                          </div>"
        );
      }
    }  
</script>                  