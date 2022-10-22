<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>
	
<P>Nous allons voir ici une astuce simple et legere pour rendre une curve maya dynamic. Cette technique ne me semble pas beaucoup utilisée, notamment parce que le hairSystem - et maintenant nHairSystem - permettent de rendre une curve dynamique de maniere beaucoup plus rapide (une lecon la dessus devrait rapidement arriver).
Le principal intéret - a mes yeux - de cette technique est qu’elle est peu gourmande en ressources.
Mais sans plus tarder, rentrons dans le vif du sujet.</P>

<dt id="20"></dt><h1>Mise en pratique</h1>
	<dt id="21"></dt><h2>Preparation de l'espace de travail</h2>

<P>De quoi allez vous avoir besoin ? Pas de grand chose de plus que des curves =) Concretement, nous allons utiliser deux curves : 
<ol>
	<li><p>la premiere fera office de guide</p></li>
	<li><p>la seconde essayera de suivre la premiere de maniere dynamique.</p></li>
</ol>
<P>
Créons donc une curve :
</P>

<?php addImage('00.png', 'Creez une curve', 240);?>

<P>Pour ma part, j’ai opté pour une curve cubic a 6 controlVertices, snapes sur ma grille, mais vous pouvez bien sur faire la forme que vous voulez.
Si vous souhaitez exactement la meme curve que moi, lancez la ligne suivante dans une fenetre Python :</P>

<?php
createCodeX("import maya.cmds as cmds
cmds.curve(p=[[0,i,0] for i in range(6)], k=[0,0,0,1,2,3,3,3], d=3, name='cv_guide')");?>

<P>Cette curve sera notre referent, notre guide. Nous pouvons donc la renommer <B>cv_guide</B>, pour plus de clarté.</P>

<?php addImage('01.png', "Pensez a renommer votre curve pour vous y retrouver par la suite");?>

	<dt id="22"></dt><h2>Ajout de la dynamique</h2>
	
<P>Il s’agit maintenant de rendre cette curve dynamique. Pour ce faire, et apres avoir au 
prealable selectionné notre <B>cv_guide</B>, nous allons nous rendre dans le menu soft/rigid 
bodies, soit via la hotBox, soit en utilisant les menus, qu’on aura préalablement positionnés 
sur ‘<I>Dynamics</I>’<br>
L’option qui nous interesse ici est « <B>Create Soft Body</B> », et nous allons bien sur jeter un oeil aux options avant toute autre chose !</P>

<?php addImage('02.png', '', 400);?>

<P>Voici ce que vous devriez avoir sous les yeux. Si vous cliquez sur les options de creation, vous 
constatez deux autres options disponibles, dont celle que nous allons choisir : <B>Duplicate, make 
copy soft.</B></P>

<?php addImage('03.png', '', 350);?>

<P>En effet, nous avons nommé notre base curve « <B>cv_guide</B> », ce qui implique que c’est elle qui fera office de guide, tandis que son duplicat sera soft, dynamique.
Et bien sur, une fois cette option validee, cochez « <B>make non-soft a goal</B> », puisque c’est exactement ce que l’on veut. Et enfin, on va passer le weight a 1.
Je ne m’étendrai pas sur les autres options, elles me semblent assez parlantes, mais si jamais, n’hésitez pas à demander des explications. </P>

<P>Pour l’heure, voici a quoi ressemble notre fenetre de creation :</P>

<?php addImage('05.png', "Voici les options que nous avons selectionne", 400);?>

<P>Il ne nous reste plus qu’a valider.</P>
<P>Aucune difference en apparence, si ce n’est que maya a créé un duplicat qui s’appelle « copyOfNomDeVotreCurve », et que cette copie dispose d’un ou plusieurs enfants, visiblement.</P>

<?php addImage('04.png', "Vous devriez voir une copie de votre curve originale dans l'outliner", 500);?>

<P>Si vous depliez la hierarchie de <B>copyOfcv_guide</B> (que j’ai renommé, pour ma part, en « <B>cv_dyn</B> » , vous constatez la presence d’un systeme de particules (profitez-en pour le renommer egalement, on garde les bonnes habitudes =)</P>

<P>Concretement, ce que fait maya, c’est qu’il va attribuer un poids a chacune de ces particules (qui correspondent aux CVs de votre curve originale). Un poids a 1 indique que la particule (et donc le controlPoint de la <B>cv_dyn</B>) va suivre completement la <B>cv_guide</B>, tandis qu’un poids a 0 indique l’inverse ; la <B>cv_dyn</B> ne suivra pas du tout la <B>cv_guide</B>
</P><P>
Il ne nous reste donc plus qu’a creer une animation sur la <B>cv_guide</B>, et a proceder au 
« skin » de la <B>cv_dyn</B>, pour voir le resultat.<br>
Je vous laisse creer l’animation que vous voulez. Pour ma part, j’ai mis une cle d’anim a la frame 0 avec toutes les translates a 0, puis une autre clé a la frame 10, avec 10 en translateX, et enfin une clé a la frame 20 avec toutes les translate a 0 a nouveau.
</P><P>
Si vous appuyez dès a present sur play, vous constatez que la <B>cv_dyn</B> suit bien la 
<B>cv_guide</B>, comme nous le voulions (pensez evidemment a configurer votre playback speed sur 
« <I>play every frame, max realtime</I> », puisque nous faisons appel a des particles)
</P>


	<dt id="23"></dt><h2>Skinning</h2>
	
<P>Nous allons maintenant ajouter un peu de detail a notre curve. Pour cela, je vous invite a 
passer en vue isolee (<B>Shift + I</B>) sur <B>cv_dyn</B>Particles :</P>

<?php addImage('06.png', 'Voici ce que vous devriez voir dans votre viewPort', 600);?>

<P>De cette maniere, vous pourrez plus facilement accéder a vos particles. Pour les selectionner, 
faites un clic droit dessus et choisissez « <B>Particles</B> » pour passer en mode de selection de 
particles.<br>
De la, il vous suffit de selectionner - par exemple - la premiere particle, et de vous rendre dans 
le <B>Component Editor</B>, dans l’onglet <B>Particles</B>.
</P><P>
La, la derniere colonne, <B>goalPP</B> (i.e. goal perParticle) indique a quel point votre particule va suivre la <B>cv_guide</B>.
<br>
De meme que pour du skin sur des joints, il vous suffit de rentrer un poids entre 0 et 1. Je vous laisse skinner chaque point de votre curve comme vous l’entendez (et c’est la que l’animation qu’on a cree va nous etre tres utile, pour juger de la reaction et du dynamisme de la curve)</P>


<dt id="30"></dt><h1>Conclusion</h1>
	
<P>Une fois satisfait de votre skinning, vous pouvez utiliser cette curve comme vous l’entendez. 
Par exemple, si vous skinnez trois joints sur <B>cv_guide</B>, vous pouvez controller cette curve, avec 
ces trois joints, et attacher une chaine de joints a la <B>cv_dyn</B> par le biais d’un ikSpline.<br>
Vous pouvez aussi combiner cette dynamique avec un control « manuel » (pour des meches de 
cheveux par exemple) a l’aide de <?php node("plusMinusAverage");?> ou <?php node("multiplyDivide");?>.</P>
<P>
Bref, maintenant que vous avez le principe, libre a vous de l’exploiter comme vous le souhaitez =)
</P>


