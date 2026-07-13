let modalAberta = false;
let pacienteAtual = null;
let intervaloConsulta = null;
let intervaloFala = null;
let tempoLembrete = null;

let vozes = [];
let sintetizador = window.speechSynthesis;

let notificacao = null;
let intervaloTitulo = null;
let tituloOriginal = document.title;
let audioAlerta = new Audio('/audio/alerta2.mp3');

function carregarVozes() {
    vozes = sintetizador.getVoices();
}

carregarVozes();

if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = carregarVozes;
}

function guardarLembrete(minutos) {
    let dados = {
        id: pacienteAtual.id,
        tempo: Date.now() + (minutos * 60 * 1000)
    };

    localStorage.setItem("lembretePaciente", JSON.stringify(dados));
}


function pacienteEmEspera(id) {
    let dados = localStorage.getItem("lembretePaciente");
    if (!dados) return false;
    let lembrete = JSON.parse(dados);

    if (Date.now() >= lembrete.tempo) {
        localStorage.removeItem("lembretePaciente");
        return false;
    }

    return lembrete.id == id;
}


function falar(texto) {
    if (!('speechSynthesis' in window)) {
        console.error("SpeechSynthesis não suportado.");
        return;
    }
    if (sintetizador.speaking) {
        sintetizador.cancel();
    }

    audioAlerta.play().catch(function () {});

    setTimeout(function () {
        const mensagem = new SpeechSynthesisUtterance(texto);
        const vozPT = vozes.find(v => v.lang.startsWith("pt"));

        if (vozPT) {
            mensagem.voice = vozPT;
        }

        mensagem.lang = vozPT ? vozPT.lang : "pt-PT";
        mensagem.rate = 0.8;
        mensagem.pitch = 1;
        mensagem.volume = 1;

        mensagem.onerror = function (e) {
            console.error("Erro ao reproduzir voz:", e ? e : "");
        };

        sintetizador.speak(mensagem);
    }, 600);
}


function iniciarMonitorizacao() {
    verificarPaciente();
    setTimeout(iniciarMonitorizacao, 2000);
}


function iniciarPiscarTitulo() {
    if (intervaloTitulo) return;

    intervaloTitulo = setInterval(function () {
        document.title = document.title === "🔴 NOVO PACIENTE" ? tituloOriginal : "🔴 NOVO PACIENTE";
    }, 1000);
}

function pararPiscarTitulo() {
    clearInterval(intervaloTitulo);
    intervaloTitulo = null;
    document.title = tituloOriginal;
}

function mostrarNotificacao(nome) {
    if (Notification.permission !== "granted") return;

    notificacao = new Notification("Sistema Hospitalar", {
        body: "Paciente " + nome + " enviado pela recepção.",
        icon: "/images/empresa/icone-hospital.png",
        requireInteraction: true
    });
    notificacao.onclick = function () {
        window.focus();
        notificacao.close();
    };
}

function pararAlertas() {
    modalAberta = false;
    clearInterval(intervaloFala);
    sintetizador.cancel();
    pararPiscarTitulo();
    if (notificacao) notificacao.close();
}
