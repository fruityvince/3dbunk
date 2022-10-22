<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>What is the Marking Menu ?</h1>
<p>Good question, the marking menu is the contextual radial menu which pops up when you right click with your mouse in Maya's viewport ;</p>
<?php addImage("whatis_hotbox.jpg", "The maring menu")?>
<p>Composed with a maximum of 8 <?php cmds("menuItem")?> which can have sub menus, their positions are called the  
<?php cmds("radial positions", "radialPosition", "menuItem")?>, named by the four cardinal points</p>
<?php addImage("hotbox_radials.jpg", "The names of the radial positions")?>

<dt id="20"></dt><h1>Defining our need</h1>
<p>Clearly, speaking of tweaking, Maya allows us to go really far, except a few static properties, alors everything can be changed. However, one element is still
static, that's the marking menu, you can edit few settings by going in the preference, but that remains very simple, which depends on the selection. This tool
literally saves you time when you know how to use it properly.</p>
<p>So we're gonna try (sucessfully =)) in this tutorial to change this static property, to be able to edit and modify it with our needs, for instance ;</p>   
<ul>
	<li>a rigger : changing the marking menu to allow hom to directly create constraints, if the selection is a joint, he can directly creates an IK</li>
	<li>an animator : allowing the animator to switch between IK / FK the selected controller, if the attribute exists, and reset the transforms in 
	translation, rotation or both</li> 
	<li>during the render process : create an exclusion light set in one click, dupe a shader of the selected object</li> 
	<li>etc...</li>
</ul>
<p>Let's try to find how Maya fills this menu and how we can change it...</p>

<dt id="30"></dt><h1>The investigation</h1>
<dt id="31"></dt><h2>The premises</h2>

<p>Obviously, the first tryouts on these researchs where far more laborious... So we'll avoid this painful part of the story and directly go to the interesting 
discoveries =) !</p>
<p>Alright, if we open our verbose friend the <b>scriptEditor</b>, activating the option <b>History</b>&rarr;<b>Echo All Commands</b>. Using the right click of the 
mouse on an empty space (everybody knows the space isn't empty, but whatever !) we should have a line in the Maya's output looking like that ;</p>
<?php createMELCodeX("buildObjectMenuItemsNow \"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\";")?>
<p>Let's create a circle and try again ;</p>
<?php createMELCodeX("buildObjectMenuItemsNow \"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\";
dagMenuProc(\"MayaWindow|<i>(...)</i>|modelPanel4|modelPanel4ObjectPop\", \"nurbsCircle1\");")?>
<p>Well well... That's interesting, the <u>contextual</u> display of the menu calls a second function, which is the <b>dagMenuProc</b> procedure, let's try
to find it using our second curious friend <?php dl("Notepad ++", "https://notepad-plus-plus.org/download")?>, searching the Maya's folder the call of this proc,
you'll find a file containing this word a lot of time, namely the <i>dagMenuProc.mel</i> file, can't be easier =). You'll find this file in 
<u>%MAYA_FOLDER%/scripts/others/dagMenuProc.mel</u>, let's open it... Aww, this is a big file !</p>

<dt id="32"></dt><h2>We found the culprit !</h2>
<p>Now that we found the correct file, let's have a walk inside if we can find some 'main' function of this whole dagMenuProc... Let's say... The biggest one,
namely <b>createSelectMenuItems</b>. This is the goal of our quest, the function called by Maya when the mouse's right button is pressed, we also note that this
functions expects two arguments<i>$parent</i> and <i>$item</i>, which are two arguments sent by Maya in the piece of code quoted above.</p>
<p>A glimpse on the function's content tells us interesting things, even if MEL language isn't the shortest language of the world, we can summarize the function
this should be something like that ;</p>  
<blockquote><b>proc createSelectMenuItems(<i>$parent, $item</i>)</b><br>
&emsp;&emsp;- Declaring the variables<br>
&emsp;&emsp;- We look at the type first argument <i>$item</i> and we define the corresponding variable to <b>True</b><br>
&emsp;&emsp;- Condition on the type of <i>$item</i><br>
&emsp;&emsp;&emsp;&emsp;We fill our marking menu with <b>menuItems</b> for each needed positions<br>
&emsp;&emsp;- We parent our new menu to <i>$parent</i><br>
</blockquote>

<dt id="40"></dt><h1>Some code</h1>
<dt id="41"></dt><h2>Hello, I'm a professional hacker</h2>
<p>Alright, now that we know which is the function called by Maya, we're simple going to redeclare it to Maya, lets open our <b>scriptEditor</b> et create a new
<i>MEL</i> tab, we're going to start with a nasty test ; redeclaring an empty function :</p> 
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){}")?>
<?php addImage("empty_proc.gif", "Overriding the marking menu")?>
<p>Now maybe we should try something better... For instance, adding a menu on the top position (<b>N</b>). If we look at the original function, we see that
a <?php cmds("menuItem")?> is declared this way ;</p> 
<?php createMELCodeX("menuItem -label \$enableIkHandle
    -annotation (uiRes(\"m_dagMenuProc.kEnableIKHandleAnnot\"))
    -echoCommand true
    -c (\"ikHandle -e -eh \" + \$handle)
    -rp \$radialPosition[4];")?>
<p>We also note that at the end, the function <b>setParent -menu $parent;</b> is called, which will parent our newly created menu to the Maya's UI.</p>
<p>Let's try to redeclare <b>createSelectMenuItems</b> as follows ;</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\";
    setParent -menu \$parent;
}")?>
<p>Ok good ! We see our first menu popping :) !</p>
<?php addImage("first_menu.jpg", "Our first custom menu")?>
<p>Thank to the <i>-command</i> argument - or simply <i>-c</i> we will be able to add a command to our function, which will be executed everytime
the menu is selected ;</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\"
		-c \"print \\\"Hello world\\\"\";
    setParent -menu \$parent;
}", "", true)?><br>
<?php addTip("Generally, if not always, in programming language the use of antislash \\ allows you to 'pass' a character, which means it won't be interpreted
by the programming language, in the previous example, the use of <b>\\\"</b> allowed us to insert <u>inside</u> the first string a second string with double
quotes.<br>
The first one is interpreted by the MEL interpreter, and the second will be interpreted when the command is executed.")?>
<dt id="42"></dt><h2>Advanced tweaking</h2>
<p>Actually, seeing the <?php cmds("menuItem")?> documentation, we can go deeper that what Maya shows us, for instance, did you ever see a marking menu
in italic ? Quite simple !</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent, string \$item){
    menuItem -label \"test\" -radialPosition \"N\"
		-c \"print \\\"Hello world\\\"\";
    menuItem -label \"italic menu\" -radialPosition \"E\"
		-c \"delete `ls -sl`\"
		-itl ;
    setParent -menu \$parent;
}", "", true)?>
<p>Ready to go =)</p>
<?php addImage("italic_menu_en.jpg")?>

<dt id="43"></dt><h2>Sub menus</h2>
<p>Implementing a sub menu is also quite simple, we just need to set the flag <i>subMenu</i> then parent our sub menus to 
the parent menu (the one on which we set the <i>subMenu</i> flag). A simple example would be ;</p>
<?php createMELCodeX("global proc createSelectMenuItems(string \$parent , string \$item ){
    menuItem -label \"test\" -radialPosition \"N\"
        -c \"print \\\"Hello world\\\"\";
        
	menuItem
		-label \"main menu\" -radialPosition \"W\" 		
		-subMenu 1;

    	menuItem
    		-label \"First sub menu\" -radialPosition \"N\"
    		-command \"print \\\"First\\\";\";
    	menuItem
    		-label \"Second sub  menu\" -radialPosition \"W\"
    		-command \"warning \\\"Second\\\";\";
    	setParent -menu ..;		
    		
    setParent -menu \$parent ;
}")?><br>
<?php addNote("The function <b>setParent -menu ..</b> will parent our menus to the first matching result in the creation hierarchy... Which is our 
'main menu' menuItem");?>
<p>And we get ;</p>
<?php addImage("sub_menu_en.gif")?>

<dt id="44"></dt><h2>Contextualization</h2>
<p>Well, our system seems to work. Nonetheless this method overrides <u>all</u> the marking menus regardless of the type of the object, to change only the marking
menu
for one object type, we need to keep the declaration of the variables such as <b>$isBezierObject</b> at the beginning of the MEL function, as well as the
condition setting the True status of the correct var ;</p>
<?php createMELCodeX("if (1 <= size(\$maskList)) {
    \$isLatticeObject = (\$maskList[0] == \"latticePoint\");
    \$isJointObject = (\$maskList[0] == \"joint\");
    \$isHikEffector = (\$maskList[0] == \"hikEffector\");
    \$isIkHandleObject = (\$maskList[0] == \"ikHandle\");
    \$isParticleObject = (\$maskList[0] == \"particle\");
    \$isSpringObject = (\$maskList[0] == \"springComponent\");
    \$isSubdivObject = (\$maskList[0] == \"subdivMeshPoint\");
    \$isLocatorObject = (\$maskList[0] == \"locator\");
    \$isMotionTrail = (\$maskList[0] == \"motionTrail\");
}
if (2 <= size(\$maskList)) {
    \$isBezierObject = (\$maskList[1] == \"bezierAnchor\");
    \$isNurbObject = (\$maskList[1] == \"controlVertex\");
    \$isPolyObject = (\$maskList[1] == \"vertex\");
}")?>
<p>And only edit the part we need to be modified in our MEL function. Which can be arduous...</p>
<p>Fortunately for you, here we are, with a ready solution to remedy this hardship. So you don't have to edit yourselves this long function, just download
this -quite- simple script <b>radialDesigner</b> and run it =)</p>  
<?php addImage("hotbox_designer.jpg")?>
<?php 
	$_GET['n'] = 'radial_designer';
	$_GET['buttons'] = true;
	include_once './dl/wrap/index.php';
?>
<p>The use could not be simpler, you just need to select in the list at top on which kind of object you want to apply the marking menu, then click on the menuItems to
edit their codes, appearance, everything, whatever, the images speak by themselves, so just have a look on this demo video =) ;</p> 

<?php addVideo("189525134")?>

<dt id="45"></dt><h2>Connecting to a scriptNode</h2>
<p>The second 'tip' of this whole story, is to put our complete redeclaration (of the <b>createSelectMenuItems</b> function) inside a scriptNode which will be
loaded each time scene is started, which can be useful in a studio for instance, when a rigger has defined properly this menu he can integrates it in his rig
scene, so every animator opening the scene will have the menu override, so they can animate better and faster... maybe stronger =).</p>
<p>The method is quite simple, given what we saw previously, we just need to create a multi-line MEL variable and put all the function inside, then we
call this variable with a <?php cmds("evalDeferred")?> which will execute the function when Maya is fully loaded, thus <u>after</u> the UI with the default
marking menu is loaded.</p>
<p>For the same reasons as before I invite you to use the <b>radialDesigner</b> to achieve that, it will spare you a looot of time, and a large amount of 
hair pulled to correctly imbricate a string into a string which is itself imbricated into a string with the antislashs =) !</p>
