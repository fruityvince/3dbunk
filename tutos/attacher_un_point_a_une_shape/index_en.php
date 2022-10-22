<?php include_once 't/head.php';?>

	
<dt id="10"></dt><h1>Concept</h1>
<dt id="11"></dt><h2>Introduction</h2>
	
<P>Hello ! We'll see in this tutorial a method pretty easy to set up as well as quite light, in order to constraint an object to the surface of another object</P>

<P>The main interest of that method consists in the fact that instead of parenting an object A to the transform of an object B, we'll parent our object A to the shape of object B. Therefore, every deformation that we'll apply on the shape of our object B (skinCluster, wrap, squash, etc) will have an effect on the position (and rotation) of object A</P>
	
<dt id="12"></dt><h2>Theory</h2>

<P>In order to achieve that, we'll use a follicle node.
For those who don't already know, a follicle is an element (made of a transform and a shape) inherited from Maya's hair system. In this context, it is a hook from where the curve driving the hair starts. The huge benefit of using it consists in the fact that - unlike many other objects in maya - the follicle is attached using UVs of the target shape. Hence no matter which deformations we'll apply on our shape, the follicle will follow. Of course, we don't care about the hair system right now, we just want to take advantage of the follicle's properties</P>

<P>To set the follicle's position, we use two parameters U and V, and not the legacy XYZ ! Therefore, this operation involves some unfolded / non-overlapped UVs at least, ideally a proper and final unfold. However, setting up a follicle manually can become a pain in the ass, considering that it's visually much easier to move an object in a 3d space than to enter some numerical values in two attributes. We'll see in the second part a way to place easily the follicle.</P>

<P>We can also notice that considering the follcile following the shape, it won't be affected by the smooth preview. As said in the name, the smooth preview is a 'preview', but doesn't create an actual node in the scene, it is just an attribute. It's important to keep in mind that if you smooth an object afterward (using the smooth preview), the follicle may have a slight offset regarding its shape.</P>

<dt id="20"></dt><h1>First part : Creation and connection of the follicle</h1>
<dt id="21"></dt><h2>Setting up the scene</h2>

<P>For this example, no need to make things too difficult ; creating a sphere in an empty scene will be more than enough, and it'll be easier to get the idea.
Create then your follicle node, either in python :
</P>
<?php createCodeX("import maya.cmds as cmds
cmds.shadingNode(‘follicle’, au=True)");?>

<P>or in the node editor (press Tab, then start writing 'follicle' to see it in the auto-completion suggestions).
Regardless the method you use, you'll see that Maya creates two elements : a shape node AND a transform node.</P>

<P>You can then set your workspace in the editor you feel the most comfortable with (node editor, hypershade, hypergraph, etc). We'll need :</P>

<OL>
	<LI>The sphere shape (we'll call it sphereShape)</LI>
	<LI>The follicle shape (we'll call it follicleShape)</LI>
	<LI>The follicle transform (we'll call it follicle)</LI>
</OL>

<?php addImage("00.png", "Node Editor");?>

<!-- <P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/00.png" NAME="attachFollicle0" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Node Editor
            </font>
    </div>
    <BR CLEAR=LEFT>
</P> -->

<dt id="22"></dt><h2>Connections and settings of the nodes</h2>

<P>As seen previously, we want to attach something to the shape, not to the transform. Therefore, it's logical that we work with the shapes.
First things first, connect the outMesh of your sphereShape to the inputMesh of the follicleShape.
A quick look to the attributeEditor will show you the two parameters that we're after, on the folliculeShape node :
</P>

<?php addImage("01.png", "FollicleShape in the attribute editor");?>

<!-- <P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/01.png" NAME="attachFollicle1" ALIGN=CENTER WIDTH=400 BORDER=0>
            <font class='alt'>
                <br>FollicleShape in the attribute editor
            </font>
    </div>
    <BR CLEAR=LEFT>
</P> -->

<P>ParameterU et ParameterV. Those two attributs are the U and V coordinates on the UVs of the sphere. So no matter which kind of deformations we'll apply to the sphere, the uvs shouldn't move, and therefore, the follicle should stay attached.
Then, we just need to connect the follicleShape to the object we want to constraint. As a convention, we constraint the follicle transform, but you can constraint anything you want.
Connect the outTranslate of the follicleShape to the translate of the follicle, and if you set your parameterU and parameterV to some coordinates where some UVs are present, your follicle should jump right to the surface of the sphere. If not, it probably stays in the middle of the scene, no matter the position of your sphere in your 3d space.
If you try to deform your shape now, you should see the follicle following.</P>

<?php addImage("02.png", "The sphere gets deformed, and the follcile follows <br>(i know, you have to watch very closely =)");?>

<!-- <P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/02.png" NAME="attachFollicle2" ALIGN=CENTER WIDTH=450 BORDER=0>
            <font class='alt'>
                <br>The sphere gets deformed, and the follcile follows <br>(i know, you have to watch very closely =)
            </font>
    </div>
    <BR CLEAR=LEFT>
</P> -->


<P>I agree that you have to watch veeeery closely, but can you see the very small red stroke that follows the deformation ?
Therefore, some of you may have noticed that as soon as we move the sphere in space, the follicle doesn't follow anymore. Not very handy... On the other hand, this is quite normal, as we didn't give any information about the sphere transform to the follicleShape. To fix this, all you need to do is to connect the worldMatrix of your sphereShape (which will, obviously, 'record' every movement of the sphere transform) to the inputMatrix of the follicleShape (for further explanation about matrices, an article should be published soon).</P>

<P>Voila ! Now, you can move your sphere in space as you want, the matrice of the shape will be influenced as well, and the same goes for the follicle =)</P>


<dt id="30"></dt>
<h1>Second part : Interactivly set the follicle's position</h1>
	<dt id="31"></dt>
	<h2>Needs</h2>
	
<P>Now that we saw the basics, we know that it's not funny nor handy to set precisely the follicle. So we'll see a method a bit more flexible to set our follicle's position.</P>

<P>As usual, let's start by 'pseudo-coding' what we want ! For this situation, what could be nice would be to have a neutral object (let's say a locator) that we'll move in translation, with the follicle corresponding to the point on the surface that is the closest from the follicle (i start from a new empty scene for this second part).
So we'll need :</P>

<OL>
	<LI>A locator, that we'll move around to 'drive' our follicle (we'll call it locDriver)</LI>
	<LI>The follicle itself, of course (we'll keep calling it follicleShape !)</LI>
	<LI>The shape on which the follicle will be attached (we'll keep calling it sphereShape !)</LI>
	<LI>The key (and mysterious ^^) element that'll allow maya to make the connection between follicle, locator and shape...</LI>
</OL>

<P>As you probably guessed already, this mysterious element is a node called closestPointOnMesh, which, as its name says, returns the closest point on a mesh. To create it, same method than usual : node editor, press TAB, and start writing 'closestPointOn...' to have access to the closestPointOnMesh via the auto-completion.</P>

<P>Let's gather all our ingredients in the node editor !</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/03.png" NAME="attachFollicle3" ALIGN=CENTER WIDTH=450 BORDER=0>
            <font class='alt'>
                <br>Tous les noeuds dont nous aurons besoin
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>The closest point on mesh will take two elements as inputs, the sphereShape and the locDriver, et ressortira le point le plus proche du locator sur la sphereShape sous forme de position dans l'espace (xyz), ou, ce qui nous intéresse ici, sous forme de paramètres U et V ! Il ne nous reste donc plus qu'à connecter tout ca...</P>
	<dt id="32"></dt>
	<h2>Connections</h2>

<P>Connect the worldPosition of the shape of the locDriver to the inPosition of the closestPointOnMesh. Then, connect the outMesh of the shpereShape to the inMesh of the closestPointOnMesh. You should see that we have the same issue than before. As long as we stay in the center of the world, that's fine, but if we move the sphere, the follicle doesn't point to the locator anymore. So connect, again, the worldMatrix of the sphereShape to the inputMatrix of the closestPointOnMesh to fix this issue.</P>

<P>With everything connected, all you need to do is to connect the result.parameterU and result.parameterV of your closestPointOnMesh respectively to the parameterU and parameterV of your follicle !</P>

<P>All those connections may seem a little bit of an overkill, but in facts, it's actually quite simple and fast to set up ! We can't explain in detail every attribute, as i consider that you know the basics already, but if no, all those operations should make sense with some practice, as it's quite self-explanatory i suppose !</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/04.png" NAME="attachFollicle4" ALIGN=CENTER WIDTH=600 BORDER=0>
            <font class='alt'>
                <br>Here is how should look your tree node after everything connected
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>And of course, once you're done with those connections, you can get rid of the closestPointOnMesh and the driverLocator and keep only the sphere and the follicle. The two first were here just to help you setting your parameterU and parameterV on the follicleShape !</P>


<BR>
<BR>
<BR>























</body>
</html></BR>