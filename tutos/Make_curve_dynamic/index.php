<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<title>Création d'une curve dynamique</title>


<br>
<br>
<div align="center">
    <font class="title">
        <center>- AJOUTER DE LA DYNAMIC A UNE CURVE -<br>Utilisation des particules pour dynamiser une curve</center>
    </font>
    <br>
    <br>
    <font class="description">
        Création et parametrage de particules pour rendre une curve dynamique
    </font>
    <br>
    <br>
</div>
<br>
<hr>
<br>
<br>
<font class='title'>
    Sommaire
</font>

<a href="<?php $actual_link;?>#10"><h1 class='sum' id='s0'>Introduction</h1></a>
	
<a href="<?php $actual_link;?>#20"><h1 class='sum' id='s1'>Mise en pratique</h1></a>
	<a href="<?php $actual_link;?>#21"><h2 class='sum' id='s7'>Preparation de l'espace de travail</h2></a>
	<a href="<?php $actual_link;?>#22"><h2 class='sum' id='s7'>Ajout de la dynamic</h2></a>
	<a href="<?php $actual_link;?>#23"><h2 class='sum' id='s7'>Skinning</h2></a>
<a href="<?php $actual_link;?>#30"><h1 class='sum' id='s1'>Conclusion</h1></a>


<br>
	
<dt id="10"></dt>
<h1>Introduction</h1>
	
<P>Nous allons voir ici une astuce simple et legere pour rendre une curve maya dynamic. Cette technique ne me semble pas beaucoup utilisée, notamment parce que le hairSystem - et maintenant nHairSystem - permettent de rendre une curve dynamique de maniere beaucoup plus rapide (une lecon la dessus devrait rapidement arriver).
Le principal intéret - a mes yeux - de cette technique est qu’elle est peu gourmande en ressources.
Mais sans plus tarder, rentrons dans le vif du sujet.</P>

<dt id="20"></dt>
<h1>Mise en pratique</h1>
	<dt id="21"></dt>
	<h2>Preparation de l'espace de travail</h2>

<P>De quoi allez vous avoir besoin ? Pas de grand chose de plus que des curves =) Concretement, nous allons utiliser deux curves : la premiere fera office de guide, tandis que la seconde essayera de suivre la premiere de maniere dynamique.
Créons donc une curve :
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/00.png" NAME="makeCurveDynamic00" ALIGN=CENTER WIDTH=240 BORDER=0>
            <font class='alt'>
                <br>Creez une curve
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Pour ma part, j’ai opté pour une curve cubic a 6 controlVertices, snapes sur ma grille, mais vous pouvez bien sur faire la forme que vous voulez.
Si vous souhaitez exactement la meme curve que moi, lancez la ligne suivante dans une fenetre Mel :</P>

<code>curve -d 3 -p 0 0 0 -p 0 1 0 -p 0 2 0 -p 0 3 0 -p 0 4 0 -p 0 5 0 -k 0 -k 0 -k 0 -k 1 -k 2 -k 3 -k 3 -k 3 ;</code>

<P>Cette curve sera notre referent, notre guide. Nous pouvons donc la renommer cv_guide, pour plus de clarté.</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/01.png" NAME="makeCurveDynamic01" ALIGN=CENTER WIDTH=240 BORDER=0>
            <font class='alt'>
                <br>Pensez a renommer votre curve pour vous y retrouver par la suite
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

	<dt id="22"></dt>
	<h2>Ajout de la dynamique</h2>
	
<P>Il s’agit maintenant de rendre cette curve dynamique. Pour ce faire, et apres avoir au prealable selectionné notre cv_guide, nous allons nous rendre dans le menu soft/rigid bodies, soit via la hotBox, soit en utilisant les menus, qu’on aura préalablement positionnés sur ‘Dynamics’
L’option qui nous interesse ici est « Create Soft Body », et nous allons bien sur jeter un oeil aux options avant toute autre chose !</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/02.png" NAME="makeCurveDynamic02" ALIGN=CENTER WIDTH=400 BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Voici ce que vous devriez avoir sous les yeux. Si vous cliquez sur les options de creation, vous constatez deux autres options disponibles, dont celle que nous allons choisir : Duplicate, make copy soft.</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/03.png" NAME="makeCurveDynamic03" ALIGN=CENTER WIDTH=350 BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>En effet, nous avons nommé notre base curve « cv_guide », ce qui implique que c’est elle qui fera office de guide, tandis que son duplicat sera soft, dynamique.
Et bien sur, une fois cette option validee, cochez « make non-soft a goal », puisque c’est exactement ce que l’on veut. Et enfin, on va passer le weight a 1.
Je ne m’étendrai pas sur les autres options, elles me semblent assez parlantes, mais si jamais, n’hésitez pas à demander des explications. </P>

<P>Pour l’heure, voici a quoi ressemble notre fenetre de creation :</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/04.png" NAME="makeCurveDynamic04" ALIGN=CENTER WIDTH=400 BORDER=0>
	<font class='alt'>
	<br>Voici les options que nous avons selectionne
        </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Il ne nous reste plus qu’a valider.</P>
<P>Aucune difference en apparence, si ce n’est que maya a créé un duplicat qui s’appelle « copyOfNomDeVotreCurve », et que cette copie dispose d’un ou plusieurs enfants, visiblement.</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/05.png" NAME="makeCurveDynamic05" ALIGN=CENTER WIDTH=500 BORDER=0>
	<font class='alt'>
	<br>Vous devriez voir une copie de votre curve originale dans l'outliner
        </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Si vous depliez la hierarchie de copyOfcv_guide (que j’ai renommé, pour ma part, en « cv_dyn » , vous constatez la presence d’un systeme de particules (profitez-en pour le renommer egalement, on garde les bonnes habitudes =)</P>

<P>Concretement, ce que fait maya, c’est qu’il va attribuer un poids a chacune de ces particules (qui correspondent aux CVs de votre curve originale). Un poids a 1 indique que la particule (et donc le controlPoint de la cv_dyn) va suivre completement la cv_guide, tandis qu’un poids a 0 indique l’inverse ; la cv_dyn ne suivra pas du tout la cv_guide

Il ne nous reste donc plus qu’a creer une animation sur la cv_guide, et a proceder au « skin » de la cv_dyn, pour voir le resultat.
Je vous laisse creer l’animation que vous voulez. Pour ma part, j’ai mis une cle d’anim a la frame 0 avec toutes les translates a 0, puis une autre clé a la frame 10, avec 10 en translateX, et enfin une clé a la frame 20 avec toutes les translate a 0 a nouveau.

Si vous appuyez des a present sur play, vous constatez que la cv_dyn suit bien la cv_guide, comme nous le voulions (pensez evidemment a configurer votre playback speed sur « play every frame, max realtime », puisque nous faisons appel a des particles)
</P>


	<dt id="23"></dt>
	<h2>Skinning</h2>
	
<P>Nous allons maintenant ajouter un peu de detail a notre curve. Pour cela, je vous invite a passer en vue isolee (shift+i) sur cv_dynParticles :</P>


<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/Make_curve_dynamic/img/06.png" NAME="makeCurveDynamic06" ALIGN=CENTER WIDTH=600 BORDER=0>
	<font class='alt'>
	<br>Voici ce que vous devriez voir dans votre viewPort
        </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>De cette maniere, vous pourrez plus facilement accéder a vos particles. Pour les selectionner, faites un clic droit dessus et choisissez « particles » pour passer en mode de selection de particles.
De la, il vous suffit de selectionner - par exemple - la premiere particle, et de vous rendre dans le component editor, dans l’onglet Particles.
La, la derniere colonne, goalPP (i.e. goal perParticle) indique a quel point votre particule va suivre la cv_guide.
De meme que pour du skin sur des joints, il vous suffit de rentrer un poids entre 0 et 1. Je vous laisse skinner chaque point de votre curve comme vous l’entendez (et c’est la que l’animation qu’on a cree va nous etre tres utile, pour juger de la reaction et du dynamisme de la curve)</P>


<dt id="30"></dt>
<h1>Conclusion</h1>
	
<P>Une fois satisfait de votre skinning, vous pouvez utiliser cette curve comme vous l’entendez. Par exemple, si vous skinnez trois joints sur cv_guide, vous pouvez controller cette curve, avec ces trois joints, et attacher une chaine de joints a la cv_dyn par le biais d’un ikSpline.
Vous pouvez aussi combiner cette dynamique avec un control « manuel » (pour des meches de cheveux par exemple) a l’aide de +/-Averages ou multiplyDivide.
Bref, maintenant que vous avez le principe, libre a vous de l’exploiter comme vous le souhaitez =)
</P>

<BR><BR>






















</body>
</html></BR>