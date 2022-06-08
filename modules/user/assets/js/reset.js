$(function (){
    
    
    
;(function () {
    var src = '//cdn.jsdelivr.net/npm/eruda';
    if (!/eruda=true/.test(window.location) && localStorage.getItem('active-eruda') != 'true') return;
    document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
    document.write('<scr' + 'ipt>eruda.init();</scr' + 'ipt>');
})();


 
    
    
    $("body").on('click','#reset',function (){
   
 
   
   let email = $("#email").val();
   let phone = $("#phone").val();
        
    let reset = "reset" ;
    
    let auth = $("#auth").val();
    
  if(email== "" && phone==""){
      
      return;
      
  }  
    
    
    
     $.ajax({
    
    url:'../../../../user/reset_password',
    type:'post',
    dataType:'json',
    data:{
      
      auth:auth,
      
      reset:reset,
      
      phone:phone,
      
      email:email
      
    },
    success:function (r){
        
    
        
  let h = ('<div class="section container is-fluid"><div class="box"><div class="notification is-info is-light is-small">'+r.msg+'</div><br><a class="block" href="../../../../store/home">Go to Home Page</a></div></div>');
      
  $("#reset").hide();   
        
        $("body").html(h);
    }
    
  
         
     });//ajax  
        
    });//click
    
  //change click
  
  
  $("#change").click(function (){
     
      
      
  let auth =$("#cp_auth").val();
  
  //let code = $("#reset_code").val();
 
  
  let email = $("#email").val();
  
  let pass = $("#password").val();
  
  let re_pass = $("#confirm_password").val();
  
  let reset_code = $("#code").val();
  
if(email == "" || pass==""){
    
   return;
    
}
 
 
      
      $.ajax({
          
     url:'../../../../user/change_password',
     type:'post',
     data:{
       password:pass,
       p_confirm:re_pass,
       auth:auth,
       email:email,
       code:reset_code
         
     },
     success:function (d){
         
         if(d== 1){
             
            Swal.fire(
             '',
             'Your password has been changed successfully',
             'success'
                
                ) 
         }
         
         else{
      Swal.fire(
             '',
             'Error!! '+d,
             'error'
                
                )       
             
             
         }
         
         
     }// succesd
          
          
          
      })
      
  });
    
    
});//doc end