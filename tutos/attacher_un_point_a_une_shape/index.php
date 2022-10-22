<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<title>Attacher un point a une shape</title>
<br>
<br>
<div align="center">
    <font class="title">
        <center>- ATTACHER UN POINT A UNE SHAPE - <br>Création d'un noeud follicle</center>
    </font>
    <br>
    <br>
    <font class="description">
        Création d'un noeud de follicle sous maya pour contraindre des elements a des shapes
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

<a href="<?php $actual_link;?>#10"><h1 class='sum' id='s0'>Concept</h1></a>
	<a href="<?php $actual_link;?>#11"><h2 class='sum' id='s7'>Introduction</h2></a>
	<a href="<?php $actual_link;?>#12"><h2 class='sum' id='s7'>Théorie</h2></a>
<a href="<?php $actual_link;?>#20"><h1 class='sum' id='s1'>Première partie : Creation et connection du follicle</h1></a>
	<a href="<?php $actual_link;?>#21"><h2 class='sum' id='s7'>Preparation de la scene</h2></a>
	<a href="<?php $actual_link;?>#22"><h2 class='sum' id='s7'>Connections et parametrage des noeuds</h2></a>

<a href="<?php $actual_link;?>#30"><h1 class='sum' id='s6'>Seconde partie : Placement interactif du follicle</h1></a>
	<a href="<?php $actual_link;?>#31"><h2 class='sum' id='s7'>Pré-requis</h2></a>
	<a href="<?php $actual_link;?>#32"><h2 class='sum' id='s7'>Connections</h2></a>





<br>
	
<dt id="10"></dt>
<h1>Concept</h1>
	<dt id="11"></dt>
	<h2>Introduction</h2>
	
<P>Nous allons voir dans ce tuto une méthode relativement rapide à mettre en place et non moins légère pour contraindre un objet a la surface d’un autre objet.</P>

<P>L'intérêt de cette méthode réside dans le fait qu’au lieu de parenter l’objetA au transform de l’objetB, nous allons parenter l’objetA a la shape de l’objetB. Ainsi, toutes les déformations appliquées sur la shape de l’objetB (skinCluster, wrap, squash, etc…) seront prises en compte et influenceront les deplacements de l'objetA.</P>
	
	<dt id="12"></dt>
	<h2>Théorie</h2>

<P>Pour arriver à nos fins, nous allons utiliser un follicle.
Pour ceux qui ne le savent pas déjà, un follicle est un élément (composé d’un transform et d’une shape) issu du système de hair de maya. Il s’agit d’un point d’accroche d’ou va partir la curve qui fait office de guide au cheveu. Tout son intérêt réside dans le fait que - contrairement à beaucoup d’autres objets dans maya - le follicule s’attache en utilisant les UVs de la shape ciblée. Ainsi, peu importe les déformations de la shape, le follicle suivra. Bien sur, le systeme de cheveux ne nous interesse pas du tout ici, on souhaite juste profiter des propriétés du follicle.</P>

<P>Le placement des follicles se fait via deux parametres U et V, et non via les traditionnels XYZ. Par consequent, cette operation impose que vous ayez des Uvs sinon propres, au moins depliés et sans overlaps. Toutefois, le placement « manuel » d'un follicle peut s'avérer pénible dans la mesure où c'est visuellement beaucoup plus simple de déplacer un objet dans l'espace que de rentrer des coordonnées numériques dans deux attributs. Nous verrons donc dans la seconde partie un moyen de placer facilement votre follicle.</P>

<P>Il est à noter par ailleurs, que étant donné que le follicle suit la shape, il sera bien évidemment insensible au smooth preview. Comme son nom l'indique, le smooth preview est un 'preview', mais ne crée aucun nœud dans la scène, il s'agit juste d'un attribut. Il est donc important de garder à l'esprit que si vous smoothez votre objet par la suite (smooth preview), le follicle pourra avoir un leger offset par rapport à la shape.</P>

<dt id="20"></dt>
<h1>Première partie : Création et connection du follicle</h1>
	<dt id="21"></dt>
	<h2>Préparation de la scène</h2>

<P>Pour l’exemple, inutile de se compliquer outre mesure ; créer une sphere dans une scene vide sera amplement suffisant, et il nous sera plus simple de comprendre le concept.
Créez ensuite votre noeud de follicle, soit en script
</P>
<?php createCodeX("import maya.cmds as cmds
cmds.shadingNode(‘follicle’, au=True)");?>

<P>soit dans le node editor (appuyez sur tab puis commencez à taper 'follicle' pour le voir apparaitre dans l'auto-complétion).
Dans les deux cas, on constate que maya crée deux éléments : un noeud de shape ET un noeud de transform.</P>
<P>Je vous invite ensuite à préparer votre espace de travail dans le node editor, l’hypershade, l’hypergraph, ou quel que soit ce que vous utilisez. Nous allons donc avoir besoin de :</P>

<OL>
	<LI>La shape de la Sphere (que nous appellerons sphereShape)</LI>
	<LI>La shape du follicle (que nous appellerons follicleShape)</LI>
	<LI>Le transform du follicle (que nous appellerons follicle)</LI>
</OL>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/00.png" NAME="attachFollicle0" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Node Editor
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

	<dt id="22"></dt>
	<h2>Connections et paramétrage des noeuds</h2>
<P>Comme vu plus haut, nous voulons ici accrocher quelque chose à la shape et non au transform, il est donc logique que nous travaillions sur les shapes.
Dans un premier temps, connectez le outMesh de la sphereShape au inputMesh du follicleShape.
Un rapide coup d’oeil dans l’attribute editor vous montrera les deux paramètres qui nous intéressent sur le node de follicleShape : 
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/01.png" NAME="attachFollicle1" ALIGN=CENTER WIDTH=400 BORDER=0>
            <font class='alt'>
                <br>Attribute Editor du follicleShape
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>ParameterU et ParameterV. Ces deux attributs correspondent aux coordonnées U et V sur les uvs de la sphère. Ainsi, peu importe les déformations que peut subir la sphère, ses UVs ne bougeront pas, et donc le follicule restera attaché.
Ensuite, il nous reste à connecter le follicleShape à l’objet qu’on souhaite contraindre. Par convention, on contraint le transform du follicle, mais vous pouvez contraindre ce que vous souhaitez.
Connectez donc le outTranslate du follicleShape au translate du follicle, et si vous avez set les parameterU et parameterV à des coordonnées sur lesquelles les UVs de la sphere passent, votre follicle devrait se trouver à la surface de votre sphere. Dans le cas contraire, il devrait rester au milieu de la scène, quelle que soit la position de votre sphere dans l’espace. 
Si vous deformez ensuite votre shape, vous constaterez que le follicle suit.</P>


<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/02.png" NAME="attachFollicle2" ALIGN=CENTER WIDTH=450 BORDER=0>
            <font class='alt'>
                <br>La sphere se déforme, et le follicle suit <br>(si si, regardez bien, ce minuscule trait rouge =)
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>Bon je vous l'accorde, il faut forcer sur les yeux, mais vous voyez le petit trait rouge qui suit la deform ?
Toutefois, les plus perspicaces auront noté que dès lors que l'on bouge la sphere dans l'espace, le follicle ne suit plus.. Genant ! Et en meme temps plutot normal, puisqu'à aucun moment on ne communique au follicleShape les informations de transform de la shape pour les baker dans les transforms de celui-ci. Pour remédier à ca, il vous suffit donc logiquement de connecter le worldMatrix de votre sphereShape (qui reprendra fatalement les déplacements du transform de la sphere) au inputMatrix du follicleShape (pour plus d'explications, un article sur les matrices devrait arriver prochainement).</P>

<P>Voilà, maintenant, vous pouvez deplacer votre sphere dans l'espace comme vous voulez, la matrice de la shape sera influencee aussi, et donc le follicle =)</P>


<dt id="30"></dt>
<h1>Seconde partie : Placement interactif du follicle</h1>
	<dt id="31"></dt>
	<h2>Pré-requis</h2>
	
<P>Maintenant qu’on a vu la base, on constate qu’il n’est ni agréable ni pratique de placer précisément notre follicle. Nous allons donc voir une méthode un peu plus souple pour placer notre follicle.</P>

<P>Comme d’habitude, commencons par mettre des mots sur ce qu’on veut. Ici, ce qui serait bien, ce serait d’avoir un objet neutre (par exemple un locator) que l’on bouge en translate, et que le follicle corresponde au point le plus proche du locator sur la surface de la sphere (je précise que je repars d'une scène vierge pour cette seconde partie).
On aura donc besoin de :</P>

<OL>
	<LI>Un locator, que l'on déplacera pour 'driver' notre follicle (que nous appellerons locDriver)</LI>
	<LI>Le follicle, bien sûr (que l'on continuera à appeler follicleShape)</LI>
	<LI>La shape sur laquelle le follicle bougera (que l'on continuera à appeler sphereShape)</LI>
	<LI>L'élément qui permettra de faire le lien entre follicle, locator, et shape...</LI>
</OL>

<P>Je peux vous dire sans plus tarder que l'élément qui fera office de lien est un node qui s'appelle closestPointOnMesh, qui, comme son nom l'indique, renvoie le point le plus proche sur un mesh. Pour le créer, toujours la meme chose : dans le node Editor, appuyez sur la touche TAB (touche a bière..), puis commencez à écrire "closestPointOn..." pour que l'auto-complétion vous suggère le closestPointOnMesh.</P>

<P>Regroupons tous nos ingredients dans le node editor</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/03.png" NAME="attachFollicle3" ALIGN=CENTER WIDTH=450 BORDER=0>
            <font class='alt'>
                <br>Tous les noeuds dont nous aurons besoin
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Le closest point on mesh va prendre en input deux éléments, la sphereShape et le locDriver, et ressortira le point le plus proche du locator sur la sphereShape sous forme de position dans l'espace (xyz), ou, ce qui nous intéresse ici, sous forme de paramètres U et V ! Il ne nous reste donc plus qu'à connecter tout ca...</P>
	<dt id="32"></dt>
	<h2>Connections</h2>

<P>Connectez donc le worldPosition de la shape du locDriver au inPosition du closestPointOnMesh. Puis connectez ensuite outMesh de la sphereShape au inMesh du closestPointOnMesh. Vous constaterez que l'on rencontre le même problème que tout à l'heure : tant qu'on reste au centre du monde, tout va bien, mais si on déplace la sphere, le follicle ne pointe plus vers le locator. Connectez donc, là encore, le worldMatrix de la sphereShape au inputMatrix du closestPointOnMesh pour régler le problème.</P>

<P>Tout ca étant connecté, il ne vous reste plus qu'à connecter le result.parameterU et result.parameterV de votre closestPointOnMesh respectivement au parameterU et parameterV de votre follicle, et le tour est joué !</P>

<P>Tout ca peut sembler compliqué, mais dans la pratique c'est très très rapide à mettre en place !</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/attacher_un_point_a_une_shape/img/04.png" NAME="attachFollicle4" ALIGN=CENTER WIDTH=600 BORDER=0>
            <font class='alt'>
                <br>Voilà à quoi devrait ressembler votre arbre après avoir tout connecté
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Et bien sûr, une fois que vous avez fini, vous pouvez vous débarrasser du closestPointOnMesh ainsi que du locator guide pour ne garder que la sphere et le follicle !</P>


<BR>
<BR>
<BR>























</body>
</html></BR>