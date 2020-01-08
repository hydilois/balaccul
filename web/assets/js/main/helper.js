// Quitter d'une cellule à l'autre lors du replissage des notes
// import './'


function next(event, line) {
    if (event.keyCode === 40) {
        var string = document.getElementById('entry' + line).value;
        if ((string !== null) && !isNaN(string)) {
            var suiv = line + 1
            if ($('#entry' + suiv) !== null) {
                $('#entry' + suiv).focus()
            }
        } else {
            alert("Incorrect value");
        }
    }
    if (event.keyCode === 38) {
        var string = document.getElementById('entry' + line).value;
        if ((string !== null) && !isNaN(string)) {
            var prev = line - 1;
            if (document.getElementById('entry' + prev) !== null) {
                document.getElementById('entry' + prev).focus()
            }
        } else {
            alert("Incorrect value");
        }
    }
}
function builSeqAbs(id, value) {
    document.getElementById('sequenceAbs_' + id).value = value
}

function collecteAbs(id) {
    var lien = document.getElementById('cibleAbs' + id).href
    var idSeq = document.getElementById('sequenceAbs_' + id).value
    lien = lien.replace("IDSEQ", idSeq)
    document.getElementById('cibleAbs' + id).href = lien
}
function builSeq(id, value) {
    document.getElementById('sequence_' + id).value = value
}

function collecte(id) {
    var lien = document.getElementById('cible' + id).href
    var idSeq = document.getElementById('sequence_' + id).value
    lien = lien.replace("IDSEQ", idSeq)
    document.getElementById('cible' + id).href = lien
}