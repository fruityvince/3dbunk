<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<title>Calculer une passe d'ambient occlusion</title>
<br>
<br>
<div align="center">
    <font class="title">
        <center>- CALCULER UNE PASSE D'AMBIENT OCCLUSION - <br></center>
    </font>
    <br>
    <br>
    <font class="description">
        Calculer une image en ambient occlusion en utilisant une texture mentalRay
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
<a href="<?php $actual_link;?>#20"><h1 class='sum' id='s1'>Configuration de base</h1></a>
<a href="<?php $actual_link;?>#30"><h1 class='sum' id='s2'>Pour aller plus loin</h1></a>
<a href="<?php $actual_link;?>#40"><h1 class='sum' id='s3'>Conclusion</h1></a>



<br>
	
<dt id="10"></dt>
<h1>Introduction</h1>
	
<P>Nous allons voir ici comment créer un rendu d’ambient occlusion pour maya / mental ray.
Si vous avez déjà une connaissance avancée du logiciel, je vous suggère de zapper ce tutoriel, qui s’adresse avant tout à des utilisateurs débutants.
</P>
<P>Je vais m’efforcer ici de rester le plus simple possible, nous n’aborderons donc ni les passes, ni les renderLayer, ni quoique ce soit d’autre qui soit plus avancé que le strict minimum requis. Sans plus tarder, commencons donc.</P>
	
<dt id="20"></dt>
<h1>Configuration de base</h1>
<P>
Pour ma part, je travaille avec maya2015 (mais la meme chose s’applique aussi bien sur les versions precedentes que suivantes (sous reserve d’installer mentalRay, qui n’est plus natif…)
Ma scene est constituée d’un cube, une sphere et un torus.
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/00.png" NAME="renduOcclu0" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Il est intéressant de noter que depuis quelques versions déja maintenant, <br>vous pouvez activer de l’ambient occlusion dans le viewport, bien que ca n’influe en rien sur le rendu.
			</font>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>
La premiere étape consiste à assigner à tous mes objets un shader. L’ambient occlusion n’ayant rien à voir avec la lumiere, inutile de partir dans du mia_material, ou meme lambert/blinn. Un simple surface shader fera tres bien l’affaire.
Je crée donc mon surface shader, que j’assigne à tous mes objets
</P>


<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/01.tiff" NAME="renduOcclu1" ALIGN=CENTER WIDTH=582 BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>
On va ensuite brancher un mib_amb_occlusion dans le outColor du surfaceShader (pensez evidemment à activer le plugin mentalRay via Window>General Editor> plugin manager, si toutefois mentalRay n’était pas activé).
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/02.tiff" NAME="renduOcclu2" ALIGN=CENTER WIDTH=582 BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>
Les parametres de l’ambient occlusion sont plutot basiques, et peu sont utiles. Nous allons en passer certains en revue
</P>

<UL>
<LI>Sampling : comme son nom l’indique, il s’agit du parametre qui va gérer le sampling de votre calcul : des petites valeurs vont produire beaucoup de grain mais permettront d’avoir un rendu tres rapide, des grandes valeurs prendront plus de temps de calcul mais vous donneront une image plus propre. Comme tout parametre de sampling, travaillez d’abord avec une valeur basse, et une fois que vous avec ce que vous voulez, augmentez le sampling.</LI>
<LI>Bright : l’ambient occlusion ressemble en général à une image en niveau de gris, le noir étant plus présent aux points de contacts entre deux surfaces. Vous pouvez changer ces couleurs par défaut (on verra une application possible en fin de tutorial). Pour l’instant, on laisse le blanc.</LI>
<LI>Dark : meme chose que pour Bright. On laisse noir 100% pour l’instant.</LI>
<LI>Spread : il s’agit du niveau d’etalement’ de votre occlusion. Plus le spread est elevé, plus la transition entre le noir et le blanc prendra de la place. Ca revient un peu à smoother la limite entre le noir et le blanc. Comme une image sera plus parlante :</LI>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/03.jpg" NAME="renduOcclu3" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Faible valeur de spread
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/04.jpg" NAME="renduOcclu4" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Valeur de spread elevée
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>
<LI>Max Distance : Jusqu’ou un objet A va affecter son objet voisin B. Pour (severement) simplier, si l’objet A se trouve à 3 unites de l’objet B, alors il n’y aura aucune trace de l’objet A sur le B (en terme d’occlu) tant que la max distance sera inferieure a 3. Une valeur de 0 indique que maya gere automatiquement la max distance. Ne soyez donc pas surpris si 0.1 donne moins de resultat que 0.</LI>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/05.jpg" NAME="renduOcclu5" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Max distance faible (1)
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/06.jpg" NAME="renduOcclu6" ALIGN=CENTER WIDTH=582 BORDER=0>
            <font class='alt'>
                <br>Max distance elevee (10)
            </font>
    </div>
    <BR CLEAR=LEFT>
</P>
<LI>Reflective : permet de prendre en compte la reflectivité dans le calcul de l’occlu. Rarement utilisé a ma connaissance.</LI>
<LI>Output mode : On le laisse le plus souvent sur 0, la valeur par défaut. Je vous invite a consulter la documentation pour plus d’infos, sachant qu’on utilise majoritairement le mode 0 (1 permet de prendre en compte l’environnement, ce qui modifie legerement le resultat quand ca marche, 2 et 3 prennent en compte les normales)</LI>
<LI>Occlusion in Alpha : sous reserve de tweaks supplémentaires, permet de rendre l’occlusion aussi en alpha. Sauf besoin bien spécifique, cette option ne nous interesse pas.</LI>
<LI>Falloff : definit - sous reserve que le max distance ne soit pas a 0 - la ‘deperdition’ de l’ambient occlusion. Un falloff faible vous donnera un rendu qui passera tres rapidement du noir au blanc, un falloff elevé fera perdurer votre gris plus longtemps avant de devenir blanc. Il s’agit de la meme chose que le falloff dans photoshop ou dans tout autre soft d’image.</LI>
</UL>


<dt id="30"></dt>
<h1>Pour aller plus loin</h1>

<P>Il y a selon moi deux intérets majeurs a sortir une passe d’occlusion (pour un débutant).
<UL>
	<LI>Montrer des élements de modélisation de maniere lisible, sans avoir à les lighter.</LI>
	<LI>Utiliser l’occlu au compositing, evidemment. Attention, defense absolue d’appliquer une occlu en produit sur l’image ! Vous allez surtout ‘salir’ votre image avec le noir, et denaturer votre rendu. Par contre, ca devient beaucoup plus interessant à utiliser en tant que masque pour retoucher le gamma, le gain, etc…</LI>
</UL>
</P>
<P>
Dans le cadre d’une utilisation au comp', voici une petite astuce, pas toujours nécessaire (d’aucuns diront meme que c’est gadget), je vous laisse decider si vous voulez l’utiliser ou non ^^
Il est tres facile d’isoler les channels rgb dans nuke. Donc plutot que de calculer une seule occlu, on peut en parametrer deux differentes, une qui communiquera dans le rouge, l’autre dans le bleu, par exemple. Ca vous permet d’avoir deux passes d’occlu differentes en un calcul, ce qui vous ouvre un peu plus de possibilites au comp'.
</P>
<P>
Alors comment ca marche. Le node mib_amb_occlusion étant une texture, on peut tout à fait en brancher deux dans un layered texture.
En combinant deux occlusions differentes (une tres sharp et l'autre beaucoup douce, par exemple), on peut les brancher dans le meme shader, pour les isoler ensuite sous nuke.
</P>
<P>
On regle donc la premiere occlusion comme on veut (cf la premiere partie du tuto). Une fois qu'on a ce qu'on veut, on remplace juste le blanc par du rouge 100% (donc 255 en R, 0 en G et 0 en B).
</P>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/07.jpg" NAME="renduOcclu7" ALIGN=CENTER WIDTH=582 BORDER=0>
	<font class='alt'>
                <br>Premiere occlusion
            </font>
	</div>
    <BR CLEAR=LEFT>
</P>
<P>
Meme chose avec la seconde occlusion, à la difference pres qu'on va cette fois parametrer le bright color en full bleu (0, 0, 255)
Une fois nos deux occlusions reglées, reste à les brancher dans un layered texture (qui, comme son nom le laisse entendre, permet d'empiler des textures selon differents modes de fusion)
</P>

<P>
Dans le node de Layered Texture, on configure le mode de fusion sur 'difference' (on ne va pas passer en revue les differents modes de fusion ou leur effet =), et on branche son outColor dans le outColor du surface shader.
</P>
<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/08.jpg" NAME="renduOcclu8" ALIGN=CENTER WIDTH=582 BORDER=0>
	<font class='alt'>
        <br>Notre tree node ressemble à ca
	</font>
	</div>
    <BR CLEAR=LEFT>
</P>

<P>
Une fois l'image calculée, vous avez évidemment quelque chose d'inutilisable tel quel.
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/09.jpg" NAME="renduOcclu9" ALIGN=CENTER WIDTH=582 BORDER=0>
	<font class='alt'>
        <br>Le rendu définitif
	</font>
	</div>
    <BR CLEAR=LEFT>
</P>

<P>
Mais si vous isolez la couche R et la couche B, vous vous retrouvez avec deux occlusions différentes, que vous pouvez combiner, blender, etc dans votre scene de comp en fonction des besoins.
</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/faire_un_rendu_docclusion/img/10.jpg" NAME="renduOcclu10" ALIGN=CENTER WIDTH=582 BORDER=0>
	<font class='alt'>
        <br>Les deux couches rouge et bleu separées.
	</font>
	</div>
    <BR CLEAR=LEFT>
</P>

<dt id="40"></dt>
<h1>Conclusion</h1>
<P>
Il y a mille et une maniere de faire une passe d’occlusion (mib_amb_occlusion, mib_fast_occlusion, via des passes, etc), et chacune a ses avantages et ses inconvenients (sauf certaines qui restent pourries =p).
L'idée ici n'etait pas de proposer une étude poussée sur l'occlusion à travers les ages, mais simplement de vous montrer une des manieres de calculer de l'ambient occlusion. Si vous etes interessés par le sujet,
il existe de nombreux articles ou vidéos detaillant tous les process possibles et imaginables, je vous encourage à vous pencher dessus si ca vous interesse.
</P>






<BR>
<BR>
<BR>























</body>
</html></BR>