<span class="boldtext">How do I add the thumbnails to my posts? </span>
<div class="indent">
  <p>Aggregate utilizes a script called TimThumb to automatically resize images. Whenever you make a new post you will need to add a custom field. Once you are on the edit/write page screen, click the "Screen Options" link on the top right of the screen and make sure "Custom Fields" is checked. Scroll down below the text editor and click on the &quot;custom fields&quot; link. In the &quot;Name&quot; section, input &quot;Thumbnail&quot; (this is case sensitive). In the &quot;Value&quot; area, input the url to your thumbnail image. Your image will automatically be resized and cropped. The image must be hosted on your domain. (this is to protect against bandwidth left) </p>
  <p><span class="style1">Important Note: You <u>must</u> CHMOD the &quot;cache&quot; folder located in the Aggregate directory to 777 for this script to work. You can CHMOD (change the permissions) of a file using your favorite FTP program. If you are confused try following <a href="http://www.siteground.com/tutorials/ftp/ftp_chmod.htm"><u>this tutorial</u></a><u>.</u> Of course instead of CHMODing the template folder (as in the tutorial) you would CHMOD the &quot;cache&quot; folder found within your theme's directory. </span></p>
</div>
<span class="boldtext">How do I add my title/logo? </span>
<div class="indent">
In this theme the title/logo is an image, which means you will need an image editor to add your own text. You can do this by opening the blank logo image located at Photoshop Files/logo_blank.png, or by opening the logo PSD file located at Photoshop Files/logo.psd. Replace the edited logo with the old logo by placing it in the following directory: theme/Aggregate/images, and naming the file "logo.png". If you need more room, or would like to edit the logo further, you can always do so by opening the original fully layered PSD file located at Photoshop Files/Aggregate.psd  </div>

<span class="boldtext"> How do I manage advertisements on my blog? </span>
<div class="indent">All of the various ad position are managed using Widgets. Within the Appearances > Widgets page in wp-admin, you will notice several widget-ready areas on the right hand side of the screen that relate to advertisements: 728x90 Leaderboard Unit, 468x60 TOp Ad Unit, and 468x60 Bottom Ad Unit. To add an advertisement into each of these sections, simply drag the "ET: Ad Block" widget into the desired ad location. Then fill in the Image URL and Image Path fields for your banner. You can also add 125x125 ads to your sidebar using the ET Advertisement widget.   </div>

  <span class="boldtext"> How do I set up the featured slider on the homepage? </span>
  <div class="indent">
  <p>The featured slider can be set up using two different methods. You can either populate the tabs using Pages, or you can popular it using posts from a designated category. In the Appearances > Aggregate Theme Options page in wp-admin, under the General Settings > Featured Slider tab, you will see an option that says "Use Pages." If you select this option then pages will be used, if you don't then posts will be used. If you want to use Pages then you simply select "Use Pages," and then below the option select which pages you would like to display in the slider. If you don't use pages, then simply select the "Featured Category" from the dropdown menu and posts from that category will be added to the slider. </p></div>
  
    <span class="boldtext"> Setting up the "recent from" sections on the homepage</span>
  <div class="indent">
  <p>The "recent from" sections on the homepage are widget-ready areas. You can add a widget into each of these three sections. We have created a custom widget called "ET: Recent From Widget" that can be added to each section via the Appearances > Widgets page. Once you add the widget to the designated area you can choose which category to pull the posts from. You are not required to add the ET: Recent From widget here, however. Any widget can be placed in these modules.</div>
  
    <span class="boldtext"> Setting up the "recent videos" and "photostream" sections</span>
  <div class="indent">
  The "photostream" section is a widget that can be added to any widget ready area in the theme. The widget is called ET Photostream. To use this widget, simply drag it from the left hand of the screen into a widget ready area on the right from within the Appearances > Widgets page in wp-admin. The widget works by pulling the most recent posts from a designated category. The widget them displays the Thumbnail image value for each.</div>
  
      <span class="boldtext"> Setting up the "recent videos" and "photostream" sections</span>
  <div class="indent">
  The "recent videos" section is a widget that can be added to any widget ready area in the theme. The widget is called ET Recent Videos Widget. To use this widget, simply drag it from the left hand of the screen into a widget ready area on the right from within the Appearances > Widgets page in wp-admin. The widget works by pulling the most recent posts from a designated category. The widget then displays a video for each post. To define a video for each post, you will need to add the et_videolink custom field to each of the posts displayed in the widget. Add a new custom field with the name "et_videolink" and then add the URL to your youtube or vimeo video within the Value field. </div>
  
   
  <span class="boldtext"> Customizing the fonts used in the theme </span>
  <div class="indent">
  <p>Aggregate makes it easy to change what fonts are used in the theme. You can change the Header and Body fonts independently from within the Appearances > Aggregate Theme Options page under the General Settings > General tab. Look for the "Header Font" and "Body Font" settings and select your desired font from the dropdown menu.
</p>
</div>

  <span class="boldtext"> Adjust the theme's background image and color</span>
  <div class="indent">
  <p>Aggregate comes with loads of background options. You can change the background color as well as choose from various overlay patterns to give your background a unique look. To adjust the background color of your theme, adjust the "Background Color" setting in ePanel located under the General Settings > General tab. When you click the field, a color wheel will pop up allowing you to choose any color.
</p>
<p>
Next you can choose a background texture via the "Background Texture" setting located in the General Settings > General Tab of ePanel. You can also upload your own background image via the "Background Image" option.</p>
</div>

  <span class="boldtext"> Using the Theme Customizer to easily experiment with font and color options</span>
  <div class="indent">
  <p>Aggregate comes with a nifty customization control panel that allows you to adjust the visual elements of your theme on the fly. This control panel makes it easier to choose great colorschemes, instead of having to adjust colors one-by-one in ePanel, and having to save/preview along the way. To enable the control panel, locate the "Show Control Panel" option in the General Settings > General Tab of ePanel. Once enabled, a settings box will appear on the left hand side of your screen when browser your website. Use the various settings to adjust your theme's colors until you have found a combination that pleases you. Then simply add the color values you have chosen into ePanel and turn off the control panel to finalize your setup.
</p>
</div>