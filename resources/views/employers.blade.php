
@extends('layouts.master')

@section('search')
	@parent
@endsection

@section('content')
<style>
    .header{
        color:#0841b0;
        font-family:Source Serif Pro;
        font-weight:600;
        font-size:2rem;
    }
    .headerMobile{
        color:#0841b0;
        font-family:Source Serif Pro;
        font-weight:600;
        font-size:1.3rem;
    }
    .subHeader{
        font-family:Source Serif Pro;
        font-size:1.6rem;
        color:#808080;
        font-weight: 700;
    }
    .subHeaderMobile{
        font-family:Source Serif Pro;
        font-size:0.9rem;
        color:#808080;
        font-weight: 700;
    }
    .header2{
        color:#0841b0;
        font-family:Source Serif Pro;
        font-weight:600;
        font-size:1.5rem;
    }
    .subHeader2{
        font-family:Source Serif Pro;
        font-size:1rem;
        color:#808080;
        font-weight: 700;
    }
    .whyAkoJobsSection{
        background-color:#F4F6F8!important;
    }
    .titleWhyAkoJobs{
        color:#0841b0;
        font-size:1.6rem;
        font-family:Source Serif Pro;
        margin-left:0%;
    }
    .titleWhyAkoJobs2{
        color:#0841b0;
        font-size:1.6rem;
        font-family:Source Serif Pro;
        margin-left:0%;
    }
    .titleWhyAkoJobs2{
         display: block;
        margin: 0 auto;
        text-align: center;
        font-family: Source Serif Pro;
        font-weight: 400;
        font-size: 1.9rem;
        color: #0841b0;
    }
    .titleWhyAkoJobs3{
         /*display: block;*/
        /*margin: 0 auto;*/
        text-align: center;
        font-family: Source Serif Pro;
        font-weight: 400;
        font-size: 1rem;
        color: #0841b0;
    }
    .featureSectionTitle{
        font-size:2rem;
        color:#0841b0;
        margin: 2rem auto 2rem 0;
    }
    .featureSectionTitle4{
        font-size:2rem;
        color:#0841b0;
        margin: 4rem auto 2rem 0;

    }
    .featureSectionTitle2{
        font-size:2rem;
        color:#0841b0;
        margin: 4rem auto 2rem 0;
        
    }
    .featureSectionTitle2Mobile{
        font-size:1rem;
        color:#0841b0;
        margin: 2rem auto 2rem 0;
    }
    .featureSectionTitle2MobileEn{
        font-size:1rem;
        color:#0841b0;
        margin: 2rem auto 2rem 0;
    }
    .featureSectionTitle3{
        font-size:2rem;
        color:#0841b0;
        margin: 2rem auto 2rem 0;
    }
    .featureSectionDescription{
        font-size:1rem;
        color:#555;
    }
    .featureSectionDescriptionList{
        font-size:1rem;
    }
    .featureSectionDescriptionListMobile{
        font-size:0.8rem;
    }
    .featureImage{
        max-width:29%;
        margin-left: 1%;
    }
    .featureImage2{
        max-width:80%;
        margin-left: 1%;
    }
    .section{
        width:85%;
    }
    .custom_iteam{
        margin-bottom: 10%;
    }
    .addArabicFont{
         font-family:Cairo;
         text-align: right;
    }
    .desktopImageSize{
        width:80%;
    }
    .rightImagesAr{
         margin-left: 23%;
    }
     .registerButton {
      background: #0841b0 !important ;
      border-color: #0841b0 !important;
      color:#fff !important;
      padding: 16px !important;
    }
    #textRegisterButton{
        font-weight: bold;
        font-size: 1rem;
    }
    .graphAr{
        max-width:35%;
    }
    .graphArMobile{
        max-width:95%;
    }
   
    
</style>
<div>
	<div class="main-container justify-content-center" id="homepage">
	    <!-- Desktop -->
    	   <div class='mt-5 text-dark col d-none d-sm-block'>
    	        
    	        <p class='text-center header {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	            <!--AKOJOBS RECRUITMENT SOLUTION-->
    	            
    	            {{app()->getLocale() == 'en'?'AKOJOBS Recruitment Solution':'خدمة ومنصة أكو جوبز للتوظيف'}}
    	        </p>
    	        <p class='text-center subHeader {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	          {{ app()->getLocale() == 'en'?"Streamline your company's recruitment process and manage your hiring from start to finish":'إدارة عملية التوظيف بسهولة وسرعة ' }} 
    	        </p>
    	        @if(app()->getLocale() == 'en')
    	       <p class='text-center subHeader {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	            We make hiring easy!
    	        </p>
    	       @endif

    	    </div>
    	<!-- Mobile -->
    	    <div class='mt-5 text-dark col d-block d-sm-none'>
    	        
    	        <p class='text-center headerMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	            <!--AKOJOBS RECRUITMENT SOLUTION-->
    	            
    	            {{app()->getLocale() == 'en'?'AKOJOBS Recruitment Solution':'خدمة ومنصة أكو جوبز للتوظيف'}}
    	        </p>
    	         <p class='text-center subHeaderMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	          {{ app()->getLocale() == 'en'?"Streamline your company's recruitment process and manage your hiring from start to finish":'إدارة عملية التوظيف بسهولة وسرعة ' }} 
    	        </p>
    	        @if(app()->getLocale()=="en")
    	       <p class='text-center subHeader2 {{ app()->getLocale()=="en"?"addArabicFont":"" }}'>
    	            We make hiring easy!
    	        </p>
    	        @endif

    	    </div>
    	<!-- Main Image --->
    	    <div class='text-center'>
    	        <div class='col-md-12 mt-5 mb-5'>
    	            <!--<img src='{{asset("employers/graph.png")}}' class='featureImage'/>-->
    	           <div calss='container'>
    	               @if(app()->getLocale() == 'ar')
    	                 <div class='d-none d-sm-block'>
    	               <img src='{{asset("employers/graphAr.png")}}'  class='featureImage {{ app()->getLocale()=="ar"?"graphAr":"" }}'/>
    	            </div>
    	            <!-- Mobile -->
    	            <div class='d-block d-sm-none'>
    	               <img src='{{asset("employers/graphAr.png")}}'  class='featureImage2 {{ app()->getLocale()=="ar"?"graphArMobile":"" }}'/>
    	            </div>
    	               @else
    	                <div class='d-none d-sm-block'>
    	               <img src='{{asset("employers/new-jobs.png")}}'  class='featureImage'/>
    	            </div>
    	            <!-- Mobile -->
    	            <div class='d-block d-sm-none'>
    	               <img src='{{asset("employers/new-jobs.png")}}'  class='featureImage2'/>
    	            </div>
    	               @endif
    	           
    	           </div>
    	        </div>
    	    </div>
    	    @php
    	    $wordAr = 'لماذا منصة أكو جوبز ؟'
    	    @endphp
    	<!-- Why ako-jobs section -->    
    	    <div class='col text-center whyAkoJobsSection p-5 mb-5'>
    	        <div calss='container'>
    	            <div calss='row'>
    	                   <div calss='col-sm-12'>
    	                     <!--<p class='mt-3 font-weight-bold titleWhyAkoJobs'>Why Akojobs Recruitment?</h1>-->
    	                     <div class='d-none d-sm-block'>
    	                             <span class='mt-3 font-weight-bold titleWhyAkoJobs2 {{ app()->getLocale()=="ar"?"addArabicFont":"" }} text-center'>{{ app()->getLocale() == 'ar'?$wordAr:'Why Akojobs Recruitment?' }}</span>
    	                             
    	                     </div>
    	                     <div class='d-none d-block d-sm-none'>
    	                             <span class='mt-3 font-weight-bold titleWhyAkoJobs3 {{ app()->getLocale()=="ar"?"addArabicFont":"" }} text-center'>{{ app()->getLocale() == 'ar'?$wordAr:'Why Akojobs Recruitment?' }}</span>
    	                     </div>
    	                   </div>
    	            </div>
    	        </div>
    	    </div>
    	 <!-- The row sections -->    
    	 <!-- Desktop section -->
    	 @if(app()->getLocale() == 'ar')
    	  <div class='container'>
    	   <!--Section one-->
    	   <div class='d-none d-sm-block custom_iteam mt-5'>
    	        <div class="row">
    	        
    	             <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                      نشر الوظائف
    	                  </p>
    	                  <ul>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'> ✓ انشر عدد غير محدود من الوظائف على موقعنا الإلكتروني</p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الوصول لآلاف المرشحين الذين يزورون موقعنا باستمرار</p></li>
    	                  </ul>
    	        </div>
    	              <div class='col-md-6 col-lg-6'>
    	            <img class='desktopImageSize' src='{{asset("employers/PostJobsAR.png")}}'/>
    	        </div>
                
    	       
            </div>
 
    	   </div>
            <!--Section tow-->
            <div class='d-none d-sm-block custom_iteam mt-5'>
              <div class="row">
               
    	      
    	         <div class='col-md-6 col-lg-6'>
    	            <!--<img class='mr-3' src='{{asset("employers/attrac.png")}}' style='margin-right:5%;'/>-->
    	            <img class='desktopImageSize rightImagesAr' src='{{asset("employers/attractAR.png")}}'/>
    	        </div>
    	          <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle2 {{ app()->getLocale()=="ar"?"addArabicFont":"" }} '>
    	                      الحصول على طلبات التوظيف
    	                  </p>
    	                  <ul>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الحصول على عدد غير محدود من طلبات التوظيف</p></li>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الإعلان عن وظائفكم على مواقع التواصل الاجتماعي الخاصة بنا</p></li>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ بناء قاعدة بيانات من المرشحين خاصة بشركتكم.</p></li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section tree -->
            <div class='d-none d-sm-block custom_iteam mt-5'>
                <div class="row">
                     
                     <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle3 {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	                    البحث في قاعدة البيانات الخاصة بأكو جوبز
    	                  <p>
    	                 <ul>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ سيتم توفير مرشحين اضافيين عن طريق قاعدة البيانات الخاصة بنا </p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ إمكانية تقييم المرشحين وإضافة الملاحظات لكل مرشح </p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ توفرأدوات بحث متقدمة لتسهيل عملية ايجاد المرشح المثالي</p></li>
    	                </ul>
    	        </div>
    	         <div class='col-md-6 col-lg-6'>
    	            <img class='desktopImageSize' src='{{asset("employers/searchAr.jpg")}}'/>
    	        </div>
                
    	       
            </div>

            </div>
            <!--Section four -->
            <div class='d-none d-sm-block custom_iteam mt-5 '>
              <div class="row">

    	       
    	                           <div class='col-md-6 col-lg-6'>
    	            <img class='desktopImageSize rightImagesAr' src='{{asset("employers/4.png")}}' style='margin-right:5%;'/>
    	        </div>
    	         <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	                       تواصل مع المرشحين وقم بإختيار موظفك الجديد
    	                  </p>
    	                 <ul>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ تواصل مع المرشحين بشكل مباشر </p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ قم بتوظيف المرشحين المؤهلين لشركتك</p></li>
    	                    
    	                </ul>
    	        </div>
    	       
            </div>

            </div>
            
            <!-- Mobile Section -->
    	   <div class='d-block d-sm-none mb-3'>
    	        <div class="row w-100 m-auto border-bottom">
                  <div class='text-center mb-3'>
    	            <img style='width:500px;' src='{{asset("employers/PostJobsAR.png")}}'/>
    	        </div>
    	        <div class='col-sm align-items-center m-auto'>
    	              
    	                   <p class='featureSectionTitle2Mobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	                      نشر الوظائف
    	                  </p>
    	                  <ul>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'> ✓ انشر عدد غير محدود من الوظائف على موقعنا الإلكتروني</p></li>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الوصول لآلاف المرشحين الذين يزورون موقعنا باستمرار</p></li>
    	                  </ul>
    	        </div>
            </div>
 
    	   </div>
            <!--Section tow-->
            <div class='d-block d-sm-none'>
              <div class="row w-100 m-auto border-bottom">
               <div class='text-center mb-3'>
    	            <img  src='{{asset("employers/attractAR.png")}}'/>
    	        </div>

    	        <div class='col align-items-center m-auto pb-3'>
    	                   <p class='featureSectionTitle2Mobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }} '>
    	                      الحصول على طلبات التوظيف
    	                  </p>
    	                  <ul>
            	           <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الحصول على عدد غير محدود من طلبات التوظيف</p></li>
            	           <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ الإعلان عن وظائفكم على مواقع التواصل الاجتماعي الخاصة بنا</p></li>
            	           <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ بناء قاعدة بيانات من المرشحين خاصة بشركتكم.</p></li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section tree -->
            <div class='d-block d-sm-none mt-5'>
                <div class="row w-100 m-auto border-bottom pb-3">
                  <div class='text-center mb-3'>
    	            <img  src='{{asset("employers/searchAr.jpg")}}'/>
    	        </div>
    	        <div class='col align-items-center m-auto'>
    	                
    	                    <p class='featureSectionTitle2Mobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }} '>
    	                     البحث في قاعدة البيانات الخاصة بأكو جوبز
    	                  </p>
    	                 
    	                 <ul>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ سيتم توفير مرشحين اضافيين عن طريق قاعدة البيانات الخاصة بنا </p></li>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ إمكانية تقييم المرشحين وإضافة الملاحظات لكل مرشح </p></li>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ توفرأدوات بحث متقدمة لتسهيل عملية ايجاد المرشح المثالي</p></li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section four -->
            <div class='d-block d-sm-none mt-2'>
              <div class="row w-100 m-auto border-bottom pb-3">
                <div class='text-center'>
    	            <img src='{{asset("employers/4.png")}}' />
    	        </div>
    	        <div class='col align-items-center m-auto'>
    	                 <p class='featureSectionTitle2Mobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	                       تواصل مع المرشحين وقم بإختيار موظفك الجديد
    	                  </p>
    	                 <ul>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ تواصل مع المرشحين بشكل مباشر </p></li>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ قم بتوظيف المرشحين المؤهلين لشركتك</p></li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <div class='text-center mt-5 mb-4'>
                <button onclick='goToRegister()' style='background-color: #0841b0; border:none; width:20%; padding:1%; border-radius: 7px;'>
                <!-- <h1 style='color:#0b2271;' class='text-bold text-center mt-5 {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>-->
                <!--  {{ app()->getLocale() == 'en'?'Register Now!':'سجل الان' }}-->
                <!--</h1>-->
                    <a href="{{ route('employers.register') }}" id='textRegisterButton' class='text-bold text-center mt-5 text-light  {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'> {{ app()->getLocale() == 'en'?'Register Now!':'أشترك' }}</a>
                </button>
    
            </div>
                           

    	     <!--<div class="row w-75 m-auto">-->
          <!--       <div class='col p-5 m-auto'>-->
    	     <!--       <p class='text-muted'>-->
    	     <!--            <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	     <!--                 Search & Filter our database-->
    	     <!--             </h4>-->
    	     <!--           <ul>-->
    	     <!--               <li>✓ We will provide you access to our database</li>-->
    	     <!--               <li>✓ Rate candidates and add notes</li>-->
    	     <!--               <li>✓ Advanced Filtration tools</li>-->
    	     <!--           </ul>-->
    	     <!--       </p>-->
    	     <!--   </div>-->
    	     <!--    <div class='col m-auto p-2'>-->
    	     <!--      <img src='{{asset("employers/searchDatabase.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	     <!--   </div>-->
          <!--  </div>-->
    	       
    	   <!--<div class='row w-100 pl-2 pr-2 pt-2'>-->
    	   <!--     <div class='col p-5 m-auto'>-->
    	   <!--        <h4 class='font-weight-bold mt-4 text-secondary d-flex align-items-center'>-->
    	   <!--            Post Unlimited Jobs-->
    	   <!--        </h4>-->
    	   <!--         <p class='text-muted'>-->
        <!--            Post unlimited jobs on our website and-->
        <!--            reach out to thousands of candidates-->
        <!--            who regularly visit our website-->
    	   <!--        </p>-->
    	   <!--     </div>-->
    	   <!--     <div class='col m-auto'>-->
    	           <!--<img src='{{asset("employers/postUnlimitedJobs.png")}}' width='95%' style='max-width:600px; margin:0 auto;' />-->
    	   <!--     </div>-->
    	   <!-- </div>-->
    	<!--    <div class='row w-100 p-5'>-->
    	<!--        <div class='col m-auto'>-->
    	<!--            <img class='ml-3' src='{{asset("employers/attrac.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	<!--        </div>-->
    	<!--        <div class='col align-items-center m-auto'>-->
    	<!--                 <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	<!--                       Attract Applicants-->
    	<!--                  </h4>-->
    	<!--                  <ul>-->
     <!--       	           <li>✓ Receive unlimited applications all in one place</li>-->
     <!--       	           <li>✓ Your post will be advertised on our social media channels</li>-->
     <!--       	           <li>✓ Build your own database of candidates to access whenever you need</li>-->
    	<!--                </ul>-->
    	<!--        </div>-->
    	<!--</div>-->
    	<!--    <div class='row w-75 m-auto'>-->
    	<!--         <div class='col p-5 m-auto'>-->
    	<!--            <p class='text-muted'>-->
    	<!--                 <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	<!--                      Search & Filter our database-->
    	<!--                  </h4>-->
    	<!--                <ul>-->
    	<!--                    <li>✓ We will provide you access to our database</li>-->
    	<!--                    <li>✓ Rate candidates and add notes</li>-->
    	<!--                    <li>✓ Advanced Filtration tools</li>-->
    	<!--                </ul>-->
    	<!--            </p>-->
    	<!--        </div>-->
    	<!--        <div class='col m-auto p-2'>-->
    	<!--           <img src='{{asset("employers/searchDatabase.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	<!--        </div>-->
    	<!--</div>-->
    	    <!--<div class='row w-100 p-5'>-->
    	    <!--    <div class='col m-auto'>-->
    	    <!--        <img class='ml-4' src='{{asset("employers/hire.png")}}' width='80%' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	    <!--    </div>-->
    	    <!--    <div class='col m-auto p-2'>-->
    	    <!--        <p class='text-muted'>-->
    	    <!--             <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	    <!--                  Hire the right candidate-->
    	    <!--              </h4>-->
    	    <!--            <ul>-->
    	    <!--                <li>✓ Communicate with candidates</li>-->
    	    <!--                <li>✓ Hire qualified candidates</li>-->
    	    <!--            </ul>-->
    	    <!--        </p>-->
    	    <!--    </div>-->
    	</div>
    	@else
    	 <div class='container'>
    	   <!--Section one-->
    	   <div class='d-none d-sm-block custom_iteam mt-5'>
    	        <div class="row">
    	             <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                       Post Unlimited Jobs
    	                  </p>
    	                  <ul>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Post unlimited jobs on our website</p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Reach out to thousands of candidates who regularly visit our website</p></li>
    	                  </ul>
    	        </div>
                  <div class='col-md-6 col-lg-6'>
    	            <img src='{{asset("employers/1.png")}}'/>
    	        </div>
    	       
            </div>
 
    	   </div>
            <!--Section tow-->
            <div class='d-none d-sm-block custom_iteam mt-5'>
              <div class="row">
                <div class='col-md-6 col-lg-6'>
    	            <!--<img class='mr-3' src='{{asset("employers/attrac.png")}}' style='margin-right:5%;'/>-->
    	            <img src='{{asset("employers/attractAR.png")}}'/>
    	        </div>
    	        <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle2 {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>
    	                       Attract Applicants
    	                  </p>
    	                  <ul>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Receive unlimited applications all in one place</p></li>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Your post will be advertised on our social media channels</p></li>
            	           <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Build your own database of candidates to access whenever you need</p></li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section tree -->
            <div class='d-none d-sm-block custom_iteam mt-5'>
                <div class="row">
                     <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle3'>
    	                       Search & Filter our database
    	                  <p>
    	                 <ul>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ We will provide you access to our database</p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Rate candidates and add notes</p></li>
    	                    <li><p class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Advanced Filtration tools</p></li>
    	                </ul>
    	        </div>
                  <div class='col-md-6 col-lg-6'>
    	            <img src='{{asset("employers/3.png")}}'/>
    	        </div>
    	       
            </div>

            </div>
            <!--Section four -->
            <div class='d-none d-sm-block custom_iteam mt-5 featureSectionTitle'>
              <div class="row">
                   <div class='col-md-6 col-lg-6'>
    	            <img src='{{asset("employers/4.png")}}' style='margin-right:5%;'/>
    	        </div>
    	        <div class='col-md-6 col-lg-6'>
    	                 <p class='featureSectionTitle4'>
    	                       Hire the right candidate
    	                  </p>
    	                 <ul>
    	                    <li><p  style='color:#4e575d;' class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Communicate with candidates</p></li>
    	                    <li><p style='color:#4e575d;'class='featureSectionDescriptionList {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Hire qualified candidates</p></li>
    	                </ul>
    	        </div>
    	       
            </div>

            </div>
            
            <!-- Mobile Section -->
    	   <div class='d-block d-sm-none'>
    	        <div class="row w-100 m-auto border-bottom">
                  <div class='text-center mb-3'>
    	            <img style='width:500px;' src='{{asset("employers/1.png")}}'/>
    	        </div>
    	        <div class='col-sm align-items-center m-auto'>
    	                   <p class='featureSectionTitle2MobileEn {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                       Post Unlimited Jobs
    	                  </p>
    	                  <ul>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Post unlimited jobs on our website</p></li>
    	                    <li><p class='featureSectionDescriptionListMobile {{ app()->getLocale()=="ar"?"addArabicFont":"" }}'>✓ Reach out to thousands of candidates who regularly visit our website</p></li>
    	                  </ul>
    	                  
    	                  
    	        </div>
            </div>
 
    	   </div>
            <!--Section tow-->
            <div class='d-block d-sm-none'>
              <div class="row w-100 m-auto border-bottom">
               <div class='text-center mb-3'>
    	            <img src='{{asset("employers/attractAR.png")}}'/>
    	        </div>

    	        <div class='col align-items-center m-auto pb-3'>
    	                   <p class='featureSectionTitle2MobileEn {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                       Attract Applicants
    	                  </p>
    	                  <ul>
            	           <li>✓ Receive unlimited applications all in one place</li>
            	           <li>✓ Your post will be advertised on our social media channels</li>
            	           <li>✓ Build your own database of candidates to access whenever you need</li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section tree -->
            <div class='d-block d-sm-none mt-5'>
                <div class="row w-100 m-auto border-bottom pb-3">
                  <div class='text-center mb-3'>
    	            <img  src='{{asset("employers/3.png")}}'/>
    	        </div>
    	        <div class='col align-items-center m-auto'>
    	                    <p class='featureSectionTitle2MobileEn {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                        Search & Filter our database
    	                  </p>
    	                 <ul>
    	                    <li>✓ We will provide you access to our database</li>
    	                    <li>✓ Rate candidates and add notes</li>
    	                    <li>✓ Advanced Filtration tools</li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <!--Section four -->
            <div class='d-block d-sm-none mt-2'>
              <div class="row w-100 m-auto border-bottom pb-3">
                <div class='text-center'>
    	            <img src='{{asset("employers/4.png")}}' />
    	        </div>
    	        <div class='col align-items-center m-auto'>
    	                   <p class='featureSectionTitle2MobileEn {{ app()->getLocale()=="ar"?"addArabicFont":"" }}' >
    	                        Hire the right candidate
    	                  </p>
    	                 <ul>
    	                    <li>✓ Communicate with candidates</li>
    	                    <li>✓ Hire qualified candidates</li>
    	                </ul>
    	        </div>
            </div>

            </div>
            <div class='text-center mt-5 mb-4'>
                <button onclick='goToRegister()' style='background-color: #0841b0; border:none; width:20%; padding:1%; border-radius: 7px;'>
                <!-- <h1 style='color:#0b2271;' class='text-bold text-center mt-5'>-->
                <!--    Register Now!-->
                <!--</h1>-->
                    <a href="{{ route('employers.register') }}" id='textRegisterButton' class='text-bold text-center mt-5 text-light'>Register</a>
                </button>
    
            </div>
                           

    	     <!--<div class="row w-75 m-auto">-->
          <!--       <div class='col p-5 m-auto'>-->
    	     <!--       <p class='text-muted'>-->
    	     <!--            <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	     <!--                 Search & Filter our database-->
    	     <!--             </h4>-->
    	     <!--           <ul>-->
    	     <!--               <li>✓ We will provide you access to our database</li>-->
    	     <!--               <li>✓ Rate candidates and add notes</li>-->
    	     <!--               <li>✓ Advanced Filtration tools</li>-->
    	     <!--           </ul>-->
    	     <!--       </p>-->
    	     <!--   </div>-->
    	     <!--    <div class='col m-auto p-2'>-->
    	     <!--      <img src='{{asset("employers/searchDatabase.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	     <!--   </div>-->
          <!--  </div>-->
    	       
    	   <!--<div class='row w-100 pl-2 pr-2 pt-2'>-->
    	   <!--     <div class='col p-5 m-auto'>-->
    	   <!--        <h4 class='font-weight-bold mt-4 text-secondary d-flex align-items-center'>-->
    	   <!--            Post Unlimited Jobs-->
    	   <!--        </h4>-->
    	   <!--         <p class='text-muted'>-->
        <!--            Post unlimited jobs on our website and-->
        <!--            reach out to thousands of candidates-->
        <!--            who regularly visit our website-->
    	   <!--        </p>-->
    	   <!--     </div>-->
    	   <!--     <div class='col m-auto'>-->
    	           <!--<img src='{{asset("employers/postUnlimitedJobs.png")}}' width='95%' style='max-width:600px; margin:0 auto;' />-->
    	   <!--     </div>-->
    	   <!-- </div>-->
    	<!--    <div class='row w-100 p-5'>-->
    	<!--        <div class='col m-auto'>-->
    	<!--            <img class='ml-3' src='{{asset("employers/attrac.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	<!--        </div>-->
    	<!--        <div class='col align-items-center m-auto'>-->
    	<!--                 <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	<!--                       Attract Applicants-->
    	<!--                  </h4>-->
    	<!--                  <ul>-->
     <!--       	           <li>✓ Receive unlimited applications all in one place</li>-->
     <!--       	           <li>✓ Your post will be advertised on our social media channels</li>-->
     <!--       	           <li>✓ Build your own database of candidates to access whenever you need</li>-->
    	<!--                </ul>-->
    	<!--        </div>-->
    	<!--</div>-->
    	<!--    <div class='row w-75 m-auto'>-->
    	<!--         <div class='col p-5 m-auto'>-->
    	<!--            <p class='text-muted'>-->
    	<!--                 <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	<!--                      Search & Filter our database-->
    	<!--                  </h4>-->
    	<!--                <ul>-->
    	<!--                    <li>✓ We will provide you access to our database</li>-->
    	<!--                    <li>✓ Rate candidates and add notes</li>-->
    	<!--                    <li>✓ Advanced Filtration tools</li>-->
    	<!--                </ul>-->
    	<!--            </p>-->
    	<!--        </div>-->
    	<!--        <div class='col m-auto p-2'>-->
    	<!--           <img src='{{asset("employers/searchDatabase.png")}}' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	<!--        </div>-->
    	<!--</div>-->
    	    <!--<div class='row w-100 p-5'>-->
    	    <!--    <div class='col m-auto'>-->
    	    <!--        <img class='ml-4' src='{{asset("employers/hire.png")}}' width='80%' width='95%' style='max-width:300px; margin:0 auto;' />-->
    	    <!--    </div>-->
    	    <!--    <div class='col m-auto p-2'>-->
    	    <!--        <p class='text-muted'>-->
    	    <!--             <h4 class='font-weight-bold mt-3 text-secondary'>-->
    	    <!--                  Hire the right candidate-->
    	    <!--              </h4>-->
    	    <!--            <ul>-->
    	    <!--                <li>✓ Communicate with candidates</li>-->
    	    <!--                <li>✓ Hire qualified candidates</li>-->
    	    <!--            </ul>-->
    	    <!--        </p>-->
    	    <!--    </div>-->
    	</div>
    	 @endif
    	  
        <!-- the row sections -->
    	    </div>

	   </div>
</div>
@endsection

@section('after_scripts')
<script>
    function goToRegister()
    {
        return window.location.href = 'https://www.akojobs.com/employers/register';
    }
</script>
@endsection
