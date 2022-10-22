<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>
	
<P>We're going to have a look on a light and simple way to make a curve dynamic in Maya. This 
technique doesn't seem very used, especially because the hairSystem - now known as nHairSystem - 
can make a curve dynamic in a very faster way (this tutorial will come soon =)). </P>
<P>The main stake, from my point of view, is the lightness of this technique, this can save some 
rigs =) !<br>So now, lets get straight to the point !</P>

<dt id="20"></dt><h1>Application</h1>
<dt id="21"></dt><h2>Setting our workspace</h2>

<P>What are you going to need ? Nothing more than few curves =), we're going to use two curves ;
</P><ol>
<li><P>the first one will be our guide</P></li>
<li><P>the second one will try to follow the first one dynamically</P></li>
</ol>
<P>Let's create a simple curve like that ; 
</P>

<?php addImage('00.png', 'Create your curve', 240);?>

<P>Personnaly I've decided to work with a cubic curve with 6 controlVertices, snapped on my grid, 
of course you can do the shape you want, if you want the exact same curve as in this example, run 
this line of code in a Python script ;</P>

<?php
createCodeX("import maya.cmds as cmds
cmds.curve(p=[[0,i,0] for i in range(6)], k=[0,0,0,1,2,3,3,3], d=3, name='cv_guide')");?>

<P>This curve will be our guide, so let's name it <B>cv_guide</B></P>


<?php addImage('01.png', "Don't forget to rename your curve =)", 240);?>


<dt id="22"></dt><h2>Adding dynamic</h2>
	
<P>Now we need to make this curve dynamic. To do so, we need to select our curve named 
<B>cv_guide</B>, then go in the menu <B>Soft/Rigid Bodies</B> if you are in the '<I>Dynamics</I>' 
workspace, otherwise by pressing <B>Space &rarr; Soft/Rigid Bodies</B>, what we're looking for in 
this menu is the tool <B>Create Soft Body</B>, let's go in the options =) !</P>


<?php addImage('02.png', '', 400);?>


<P>This is what you should see, if you click on the <B>Creation options</B>, you see there is two 
other options available, the one we need here is <B>Duplicate, make copy soft</B> !</P>


<?php addImage('03.png', '', 350);?>


<P>Indeed, we've named our first curve <B>cv_guide</B>, so this is gonna be our guide, and this 
one, the duplicate will be soft, dynamic. Of course, once you selected this option, check the <B>Make 
non-soft a goal</B> box below, since this is exactly what we want. Finally you can set the 
<B>weight</B> to 1.<br>
We're not going to detail all the other options, they are quite obvious, but well, if ever you have 
trouble, don't hesitate to ask for help =) !</P>

<P>So now our <B>Create Soft Body</B> window should looks something like ; </P>


<?php addImage('04.png', 'This is our final options selected', 400);?>


<P>We just have to press <B>Create</B> now !</P>
<P>Apparently no change have occured, except that Maya made a duplicate of our curve called 
<B>copyOfTheNameOfYourCurve</B>, this duplicate seems to have more than one child because we can 
expand it ;</P>


<?php addImage('05.png', "You should see a copy of your original curve in the Outliner", 500);?>


<P>If you unfold the hierarchy of <B>copyOfcv_guide</B> (I renamed it to <B>cv_dyn</B>) you'll see 
a particle system in it (you should also rename the particle system, let's keep the good habits =))</P>

<P>So what Maya is doing internally, is to set a weight on each of these particles (which match 
with the CVs of your original curve). If the weight is 1, this means the particle (and so the 
<I>controlPoint</I> of the <B>cv_dyn</B>) will follow completely the <b>cv_guide</b>, whereas if 
the weight is 0 the result is opposite ; the curve <B>cv_dyn</B> doesn't follow <B>cv_guide</B> at 
all.</P>
<P>So now we just have to create an animation on our <B>cv_guide</B>, to make some 'skinning' of 
our curve <B>cv_dyn</B>, to see the result ! Feel free to create a simple animation as you want. 
I just set one keyframe at frame 0 with all translate to 0, then an other key at frame 10 in 
translateX, and a last one at frame 20 with all translates to 0 again.
</P>
<P>If you press <B>Play</B> you will see your <B>cv_dyn</B> following the <B>cv_guide</B>, as we 
wanted (don't forget to set your playback speed on <I>play every frame, max realtime</I> since we 
are working with particles).
</P>


<dt id="23"></dt><h2>Skinning</h2>
	
<P>Now we're going to add some details on our curve. To do so, I suggest you to isolate your 
<B>cv_dynParticles</B>, the shortcut is <B>Shift + I</B></P>


<?php addImage('06.png', 'This is what you should see in your viewPort', 600);?>


<P>This way you can access easily to your particles. To select them, press <B>RMB</B> above and 
choose <B>Particles</B> to go in particles selection mode.
<br>
Now you just have to select - for instance - the first particle, then go in the <B>Component 
Editor</B> in the <B>Particles</B> tab.
</P>
<P>
Here you can see the last column of the table is '<B>goalPP</B>' (i.e. goal perParticle), these 
values indicates how much your particle will follow the <B>cv_guide</B> curve.
<br>
This is the same principle as for skinning with joints, you just have to set a weight between 0 and 
1. Feel free to set these values as you want, this is where our animation we've made is becoming 
useful, now we can judge the dynamic !</P>


<dt id="30"></dt><h1>Ending</h1>
	
<P>Since you're satisfied with your skinning, you can use your new crazy dynamic curve as you 
want. For instance, if you skin your <B>cv_guide</B> curve with 3 joints, you can now control the 
main curve with these joints, then you attach a chain of joints on your curve <B>cv_dyn</B> with.. 
let's say, an ikSpline.<br>
You can also combine this dynamic with a "manual" control (for a simple hair rig for instance) with 
some <?php node("plusMinusAverage");?> or <?php node("multiplyDivide");?>.
</P>
<P>
Well, now you've got it, feel free to use this technique as you want =) !
</P>

