<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Synopsis</h1>
<p>In this tutorial we're going to learn a very simple system to blend 
a curve from hard angles to smooth ones, which can be easily upgraded
to a nurbsSurface. This can have various uses, but it's maintly for 
rigging purposes, this allows you to control if you want straight or 
round angles, for instance if you want to change the shape of your
character's fingers. Or for a spine, straight or smooth =).</p>

<dt id="20"></dt><h1>Concept</h1>
<dt id="21"></dt><h2>Method</h2>
<p>Our method will follows these few steps</p>
<p>Starting from a skinned curve, we rebuild it to have a smooth copy,
then this copy will be rebuilded a second time with a large amount of
knots to keep the shape and match with the original one. Thus the first
one will be rebuilded the same way, to keep the angles hard, then a 
blendShape between the two curves should do the job ! Let's do it.</p>

<dt id="22"></dt><h2>I dupe, you dupe, he dupes...</h2>
<p>
	Let's start by creating a simple curve with <b>1</b> degree.
</p>
<?php addImage("01.gif")?>
<p>Adding some joints and finalizing with a skinCluster we should
obtain a wonderful curve rig ;</p>
<?php addImage("02.gif")?>
<p>Here is a 'script' version of the previous steps ;</p>
<?php

createCodeX ( "from maya import cmds

positions = [(i, 0, 0) for i in range(4)]

crv = cmds.curve(p=positions, d=1, n='crv_origin')

cmds.select(cl=True)
jts = [cmds.joint(p=pos, n='jt_skin_%02d' % pos[0]) for pos in positions]

cmds.skinCluster(crv, jts, tsb=True, mi=1)", "Creating and skinning a curve", true )?>
<p>
	Let's open the <b>Rebuild Curve</b> tool of Maya in order to create a new 
	rounded version, to do that press <b>Space Bar &rarr; Edit Curves &rarr;
	Rebuild Curve &rarr; ...</b> and open the options window. Activate the <i>
	Uniform</i> mod and set the number of degree to <i>3</i> to have a rounded
	copy. The <i>Number of spans</i> should be the number of knots less 1.
</p>
<p>
	Don't forget to activate the <i>Keep original</i> option !
</p>

<?php addImage("03.jpg", "Rebuild Curve Options")?>

<?php addTip ( "The <b>Keep original</b> option, exists in several Maya's tools, it allows you to 
		create a copy of the object which keeps using the previous deformers and attributes, can
		be useful when you want a 'dynamic' copy of your object." )?>

<dt id="23"></dt><h2>Conforming</h2>
<p>
	We have our two curves, let's name them '<b>crv_origin</b>' and '<b>crv_smooth</b>'.
	The rounded one and the original one with straight angles. The problem is, if we
	want to create a blendShape between these two curves, the number of points doesn't
	match so the result won't be relevant at all. To prevent this correct this we need 
	to conform the number of knots between the two curves. Then we'll be able to perform
	a blendShape.
</p>
<p>
	Let's do two more <b>Rebuild Curve</b> with same settings for both, the one
	above the origin curve the second one on our rounded one.
</p>
<ul>
	<li>With a big about of spans (ideally this amount should be a multiple
	of the original number of spans, in our example we've made ten times more,
	so 30 at the end.</li>
	<li>Desactivate the <i>Keep original</i> option</li>
	<li>Reduce the number of degrees to 1, we'll try to keep it light, yeah
	actually we don't it to keep it light for this example, but let's say you
	need to rig all the whale's teeth with this method.. You'll need a light
	solution !</li> 
</ul>
<?php addImage("04.jpg", "Awesome Rebuild Curve options")?>
<p>We can now create a blendShape between our two curves...</p>
<dt id="30"></dt><h1>Finishing</h1>
<p>
	Alright ! Let's select the rounded curve '<b>crv_smooth</b>' and the straight
	one '<b>crv_origin</b>', and create a <i>blendShape</i> by pressing <b>
	Space Bar &rarr; Create Deformers &rarr; Blend Shape</b>.
	Fine, now we have a blendshape to transform our curve with hard angles to smooth
	ones !
</p>
<p>Let's have a try...</p>
<p>Ouch !!!</p>
<dt id="31"></dt><h2>The little stone in our machinery</h2>
<?php addImage("05.jpg")?>
<p>
	Hmmm... Maya doesn't seems happy at all ! The worst of all errors is
	above our heads ! The <b>Cycle</b> !!
</p>
<p>
	How could we solve that ?... In this case, this won't be much complicated,
	Maya is just trying to make a blendShape from two objects but one of these
	objects refers to the other one, so he's trying to refer to the other in
	a big bad loop... To solve that, we need to reconnect the nodes by changing
	the source of our first <?php node("rebuildCurve")?> node on the rounded curve.
</p>
<dt id="32"></dt><h2>Sweeping the stone</h2>
<p>
	Using the <b>Node Editor</b>, select the original curve, namely 
	'<b>crv_origin</b>' and graph it's shape, you should see something like
	that ;
</p>
<?php addImage("06.jpg")?>
<p>
	We clearly see that the output of our original curve's shape goes inside 
	the <?php node("rebuildCurve")?> which is itself referenced in the blendShape node,
	conducting to an infinite loop, but we don't need all the informations of
	the original curve's shape, just the ones after the skinCluster, so we're
	going to reconnect the <b>outputGeometry</b> of our skinCluster to the 
	<b>inputCurve</b> of our rebuildCurve, like that ;
</p>
<?php addImage("07.jpg")?>
<p>Or you can simply tip these few lines in Maya, this will do the job ;</p>
<?php

createCodeX ( "skincluster = 'skinCluster1'        # the name of your original skinCluster
rebuild = 'rebuildCurve3'           # the name of your smoothed rebuildCurve
cmds.connectAttr('%s.og[0]' % skincluster, '%s.ic' % rebuild, f=True)" )?>
<p>
	That's it ! Now hide the '<b>crv_smooth</b>' and you can play with the 
	weight of you blendShape's target to switch your curve from a rounded one
	to a straight-angled one, keeping the previous deformers which were on the
	curve '<b>crv_origin</b>' ! Like that ; 
</p>
<?php addImage("08.gif")?>
<dt id="32"></dt><h2>Code & Extending</h2>
<p>Well... for the most lazy ones, here is the full code for this tutorial =) !
This is a simple example which shows you the importance of the deformers' stack,
the order and interoperability between it's elements and the different objects
of you scene.</p>
<?php

createCodeX ( "from maya import cmds

length = 4      # number of point of you curve

# default parameters of our rebuildCurve
default_params = {'rt': 0, 'end': 1, 'kr': 0, 'kcp': 0,
		'kep': 1, 'kt': 0, 'tol': 0.01, 'ch': 1}

# our knots' positions
positions = [(i, 0, 0) for i in range(length + 1)]

crv = cmds.curve(p=positions, d=1, n='crv_origin')

cmds.select(cl=True)
jts = [cmds.joint(p=pos, n='jt_skin_%02d' % pos[0]) for pos in positions]

skincluster = cmds.skinCluster(crv, jts, tsb=True, mi=1)[0]
crv_smooth, rebuild = cmds.rebuildCurve(crv, s=length - 1, d=3, rpo=0, **default_params)

# we conform the two curve in order to have the same number of knots
cmds.rebuildCurve(crv, s=length * 10, d=1, rpo=1, **default_params)
cmds.rebuildCurve(crv_smooth, s=length * 10, d=1, rpo=1, **default_params)

# finishing 
cmds.blendShape(crv_smooth, crv)
cmds.connectAttr('%s.og[0]' % skincluster, '%s.ic' % rebuild, f=True)

# hiding the smoothed curve
cmds.setAttr('%s.v' % crv_smooth, 0)

# for our current example
cylinder = cmds.polyCylinder(r=0.2, h=length, sx=10, sy=length * 10, sz=1, ax=(1, 0, 0))
cmds.move(length / 2, 0, 0, cylinder, r=True)
wire = cmds.wire(cylinder, w=crv)
cmds.setAttr('%s.dropoffDistance[0]' % wire[0], 10)", "Curve hard 'n round", true )?>
<br>
<?php
addNote ( "For this example we're creating a simple cylinder with a wire deformer in 
order to highlight curve's changes. Move the joints and select the curve to change
the strength of the angles !")?>
<h2>Extending</h2>
<p>
	Note that this tutorial can be easily extended to nurbsSurfaces, the
	principle is exactly the same with the <?php node("rebuildSurface")?> tool.</b>