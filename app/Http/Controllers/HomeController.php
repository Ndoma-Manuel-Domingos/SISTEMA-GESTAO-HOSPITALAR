<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\AnoLectivo;
use App\Models\AnuncioAdmin;
use App\Models\Atendimento;
use App\Models\BackupSetting;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cargo;
use App\Models\Cliente;
use App\Models\ClienteContrato;
use App\Models\Configuracao;
use App\Models\Consulta;
use App\Models\Contrato;
use App\Models\Membro;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Documento;
use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Exame;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\FichaTriagem;
use App\Models\Fornecedore;
use App\Models\Funcionario;
use App\Models\Internamento;
use App\Models\ItemVenda;
use App\Models\Ingrediente;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Medico;
use App\Models\Mesa;
use App\Models\Morgue;
use App\Models\MotivoAusencia;
use App\Models\MotivoSaida;
use App\Models\Obito;
use App\Models\Ocorrencia;
use App\Models\OperacaoFinanceiro;
use App\Models\PacoteSalarial;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Registro;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\TaxaIRT;
use App\Models\Turma;
use App\Models\Turno;
use App\Models\PlanoTratamento;
use App\Models\Producao;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\ResultadoExame;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gerarDocumentoExcelProduto()
    {
        // Lista dos produtos
        $produtos = [
            ['pimenta em po dume', '100.00', "P", '0.00', '0.00'],
            ['açafrao em po dume', '100.00', "P", '0.00', '0.00'],
            ['chouriço corrente extra 150g', '800.00', "P", '0.00', '0.00'],
            ['banana', '500.00', "P", '0.00', '0.00'],
            ['pimenta', '150.00', "P", '0.00', '0.00'],
            ['cebola', '100.00', "P", '0.00', '0.00'],
            ['cebola', '150.00', "P", '0.00', '0.00'],
            ['pepino', '200.00', "P", '0.00', '0.00'],
            ['sardinha tamima 125g', '700.00', "P", '0.00', '0.00'],
            ['palitos de trigo sabor picante koyasa 50g', '400.00', "P", '0.00', '0.00'],
            ['palito de trigo sabor alga kuyosa 50 g', '400.00', "P", '0.00', '0.00'],
            ['chocolate tayas orient hazelnut', '800.00', "P", '0.00', '0.00'],
            ['leite pascual sabor morango 200ml', '300.00', "P", '0.00', '0.00'],
            ['leite açucarado 200ml pascual', '300.00', "P", '0.00', '0.00'],
            ['leite com chocolaten 200l pascual', '300.00', "P", '0.00', '0.00'],
            ['biscoito wafer morango alp', '100.00', "P", '0.00', '0.00'],
            ['sabonete fofo aloe vera', '250.00', "P", '0.00', '0.00'],
            ['sabobete fofo tratamento de amendoa', '250.00', "P", '0.00', '0.00'],
            ['dedorizante suave de leite ,corpo a corpo', '1650.00', "P", '0.00', '0.00'],
            ['dedorizante bela e natural ,corpo a corpo', '1650.00', "P", '0.00', '0.00'],
            ['cha biba', '50.00', "P", '0.00', '0.00'],
            ['detergente ama 15g', '25.00', "P", '0.00', '0.00'],
            ['biscoito gira bola 100g', '200.00', "P", '0.00', '0.00'],
            ['caderno fushi', '750.00', "P", '0.00', '0.00'],
            ['refrigerante fanta laranja pet 280ml', '300.00', "P", '0.00', '0.00'],
            ['biscoito integral cacau 150g', '600.00', "P", '0.00', '0.00'],
            ['sumo cana joy manga 500ml', '350.00', "P", '0.00', '0.00'],
            ['biscoito wafer vanila alp 100g', '100.00', "P", '0.00', '0.00'],
            ['margarina jadida 250g', '1650.00', "P", '0.00', '0.00'],
            ['sumo sagiko manga', '650.00', "P", '0.00', '0.00'],
            ['leite condensado biba 300g', '650.00', "P", '0.00', '0.00'],
            ['leite meio gordo aumi 1L', '1000.00', "P", '0.00', '0.00'],
            ['fermento em po bom apetite 50g', '250.00', "P", '0.00', '0.00'],
            ['mayoneise kombo 25g', '100.00', "P", '0.00', '0.00'],
            ['pastilha blox melacia', '150.00', "P", '0.00', '0.00'],
            ['pastilha blox peppermint', '150.00', "P", '0.00', '0.00'],
            ['smirnoff ice double black 330ml', '800.00', "P", '0.00', '0.00'],
            ['biscoito glucose leite e mel 27g', '60.00', "P", '0.00', '0.00'],
            ['garoto bon barra de jinguba', '400.00', "P", '0.00', '0.00'],
            ['lampada incandescente ros bien 100w', '300.00', "P", '0.00', '0.00'],
            ["yougorte ultra mel manga 125g", "700.00", "P", "0.00", "0.00"],
            ["yougorte ultra mel leite 125g", "700.00", "P", "0.00", "0.00"],
            ["sumo cana joy tutti 330ml", "250.00", "P", "0.00", "0.00"],
            ["yougorte tropical ultra mel 125g", "700.00", "P", "0.00", "0.00"],
            ["caderno escolar 80 p", "150.00", "P", "0.00", "0.00"],
            ["sumo tutti frutti nutry 1L", "1,600.00", "P", "0.00", "0.00"],
            ["sumo de laranja fresh 9g", "100.00", "P", "0.00", "0.00"],
            ["biscoito de maracuja wafer", "100.00", "P", "0.00", "0.00"],
            ["cerveja doppel em lata 330ml", "500.00", "P", "0.00", "0.00"],
            ["sumo fresh de banana", "100.00", "P", "0.00", "0.00"],
            ["sardinha uami 125g", "700.00", "P", "0.00", "0.00"],
            ["mayoneise mamamia 25ml", "100.00", "P", "0.00", "0.00"],
            ["BEBIDA", "600.00", "P", "0.00", "0.00"],
            ["refrigerante coca cola pet 280ml", "250.00", "P", "0.00", "0.00"],
            ["sumo de maça 1L natureza", "950.00", "P", "0.00", "0.00"],
            ["sabonete germol melhorado 75g", "350.00", "P", "0.00", "0.00"],
            ["lava loica frutos vermelho ultra 750ml", "850.00", "P", "0.00", "0.00"],
            ["lava loica lavanda ultra 750ml", "850.00", "P", "0.00", "0.00"],
            ["lava loica frutos vermelho ama 400ml", "500.00", "P", "0.00", "0.00"],
            ["lava loica amizade com limao ama 400ml", "500.00", "P", "0.00", "0.00"],
            ["lava loica harmonia com lavanda ama 400ml", "500.00", "P", "0.00", "0.00"],
            ["matega alimo azul 35% 250g", "550.00", "P", "0.00", "0.00"],
            ["cola rapida super glue 3g", "100.00", "P", "0.00", "0.00"],
            ["pasta de dente cravo da india e canela special 100g", "650.00", "P", "0.00", "0.00"],
            ["lenços de bolso premium", "200.00", "P", "0.00", "0.00"],
            ["corn flakes bom dia 250g", "1000.00", "P", "0.00", "0.00"],
            ["sumo de maracuja fresh 9g", "100.00", "P", "0.00", "0.00"],
            ["biscoito de chocolate likon 65g", "150.00", "P", "0.00", "0.00"],
            ["detergente linda 30g", "50.00", "P", "0.00", "0.00"],
            ["reboçado mentol shaltoux", "50.00", "P", "0.00", "0.00"],
            ["atum uami 142g", "1100.00", "P", "0.00", "0.00"],
            ["guardanapo linda G", "500.00", "P", "0.00", "0.00"],
            ["penso higenico intima", "500.00", "P", "0.00", "0.00"],
            ["guardanapo nepia G", "350.00", "P", "0.00", "0.00"],
            ["biscoitos coco 85G", "150.00", "P", "0.00", "0.00"],
            ["biscoitos sabor de leite 85G", "150.00", "P", "0.00", "0.00"],
            ["biscoito yummy morango 70g", "150.00", "P", "0.00", "0.00"],
            ["mateiga 60% gordura alimo 450g", "1250.00", "P", "0.00", "0.00"],
            ["sal sel fina 250g", "150.00", "P", "0.00", "0.00"],
            ["massa esparguete 350g", "400.00", "P", "0.00", "0.00"],
            ["biscoito agua e sal integral renata 170g", "650.00", "P", "0.00", "0.00"],
            ["leite condensado uami 150g", "450.00", "P", "0.00", "0.00"],
            ["sumo de ananas gold", "100.00", "P", "0.00", "0.00"],
            ["sumo de laranja gold", "100.00", "P", "0.00", "0.00"],
            ["sumo de manga gold", "100.00", "P", "0.00", "0.00"],
            ["farinha de trigo extra fina tio luca 1kg", "900.00", "P", "0.00", "0.00"],
            ["biscoito digestivo likon 250g", "800.00", "P", "0.00", "0.00"],
            ["maionese mebon f 250ml", "1200.00", "P", "0.00", "0.00"],
            ["biscito footbal coco 100g", "200.00", "P", "0.00", "0.00"],
            ["acucar kapanda 1kg", "1150.00", "P", "0.00", "0.00"],
            ["dedorizante rox powerful 50g", "800.00", "P", "0.00", "0.00"],
            ["pasta de dente spacial sal dos himalaias 100g", "700.00", "P", "0.00", "0.00"],
            ["pasta de dente cravo da india special 100g", "700.00", "P", "0.00", "0.00"],
            ["milho doce orizon 400g", "750.00", "P", "0.00", "0.00"],
            ["sardinha tvelho", "700.00", "P", "0.00", "0.00"],
            ["refrigerante blue laranja 1,5l", "1100.00", "P", "0.00", "0.00"],
            ["refrigerante blue coco ananas 1,5l", "1100.00", "P", "0.00", "0.00"],
            ["chocolante super peanuit", "100.00", "P", "0.00", "0.00"],
            ["arroz tio lucas 1kg", "1350.00", "P", "0.00", "0.00"],
            ["sumo natureza de laranja 200ml", "200.00", "P", "0.00", "0.00"],
            ["margarina soya 250g", "800.00", "P", "0.00", "0.00"],
            ["arroz parborizado patriota 1kg", "1150.00", "P", "0.00", "0.00"],
            ["refrigerante la vita manga 350ml", "200.00", "P", "0.00", "0.00"],
            ["refrigerante la vita maracuja 350ml", "200.00", "P", "0.00", "0.00"],
            ["grao de bico orizon 400g", "850.00", "P", "0.00", "0.00"],
            ["massa tomate bom apetite p 70g", "150.00", "P", "0.00", "0.00"],
            ["salsicha alimo 400g", "1450.00", "P", "0.00", "0.00"],
            ["freegells", "250.00", "P", "0.00", "0.00"],
            ["lixivia lava 1L", "600.00", "P", "0.00", "0.00"],
            ["fralda teu bebe", "125.00", "P", "0.00", "0.00"],
            ["biscoito marie crisp 65g", "150.00", "P", "0.00", "0.00"],
            ["biscoito digestivo mini 45g", "100.00", "P", "0.00", "0.00"],
            ["refrigerante la vita laranja 350 ml", "200.00", "P", "0.00", "0.00"],
            ["refrigerante la vita ananas 350ml", "200.00", "P", "0.00", "0.00"],
            ["refrigerante la vita uva 350ml", "200.00", "P", "0.00", "0.00"],
            ["vinho tinto alandra 750ml", "3900.00", "P", "0.00", "0.00"],
            ["batata frita de arroz kuyosa sabor bife 80g", "400.00", "P", "0.00", "0.00"],
            ["batata frita de arroz kuyosa sabor picante 80g", "400.00", "P", "0.00", "0.00"],
            ["milho para pipoca alimo 500g", "1000.00", "P", "0.00", "0.00"],
            ["leite e fruta de manga ,lulu 200ml", "150.00", "P", "0.00", "0.00"],
            ['COLA EPOXY GLUE 28G', 800.00, "P", 0.00, 0.00],
            ['maionese delicia 25ml', 100.00, "P", 0.00, 0.00],
            ['bolo dimbo cupcake', 150.00, "P", 0.00, 0.00],
            ['mistura para o bolo laranja renata 400g', 1450.00, "P", 0.00, 0.00],
            ['mistura de bolo chocolate renata 400g', 1450.00, "P", 0.00, 0.00],
            ['mistura de bolo baunilha renata 400g', 1450.00, "P", 0.00, 0.00],
            ['biscoito vanila renata 112g', 500.00, "P", 0.00, 0.00],
            ['cafe 3em1 nescafe 20g', 200.00, "P", 0.00, 0.00],
            ['cafe classic nescafe 50g', 500.00, "P", 0.00, 0.00],
            ['massa esparguete alice 300g', 350.00, "P", 0.00, 0.00],
            ['sabonete bonita lavender 125g', 550.00, "P", 0.00, 0.00],
            ['sabonete bonita limao 125g', 550.00, "P", 0.00, 0.00],
            ['farinha de trigo patriota 1kg', 800.00, "P", 0.00, 0.00],
            ['atum polo star 160g', 1200.00, "P", 0.00, 0.00],
            ['atum atlanta 112g', 1200.00, "P", 0.00, 0.00],
            ['acucar alimo 1kg', 1100.00, "P", 0.00, 0.00],
            ['bolo vanila dimbo', 150.00, "P", 0.00, 0.00],
            ['dedorizante fresh natural nivia 50ml', 2000.00, "P", 0.00, 0.00],
            ['dedorizante deep nivia 50ml', 2000.00, "P", 0.00, 0.00],
            ['toothpick', 300.00, "P", 0.00, 0.00],
            ['pasta de dente charcol colgate 35g', 250.00, "P", 0.00, 0.00],
            ['esponja fu shi', 150.00, "P", 0.00, 0.00],
            ['cotonete cozy baby', 250.00, "P", 0.00, 0.00],
            ['bolo dimbo banana', 150.00, "P", 0.00, 0.00],
            ['milby arroz', 350.00, "P", 0.00, 0.00],
            ['milby mel', 350.00, "P", 0.00, 0.00],
            ['milho doce gold 340g', 800.00, "P", 0.00, 0.00],
            ['papa nestum completo de bolacha 40g', 300.00, "P", 0.00, 0.00],
            ['ambientador perfume lavanda bifine 300ml', 1250.00, "P", 0.00, 0.00],
            ['anbientador perfume morango bifine 300ml', 1250.00, "P", 0.00, 0.00],
            ['ambietador perfume limao bifine 300ml', 1250.00, "P", 0.00, 0.00],
            ['refrigerante red cola 400ml', 250.00, "P", 0.00, 0.00],
            ['pomada de sapato castanho lude', 500.00, "P", 0.00, 0.00],
            ['biscoito chocolate amanteigado renata 133g', 550.00, "P", 0.00, 0.00],
            ['tempero serra 750ml', 2900.00, "P", 0.00, 0.00],
            ['bolo dimbo coco', 150.00, "P", 0.00, 0.00],
            ['guarda napo linda p', 300.00, "P", 0.00, 0.00],
            ['leite evaporado momo 100g', 600.00, "P", 0.00, 0.00],
            ['leite gordo momo 250g', 1950.00, "P", 0.00, 0.00],
            ["fuba de milho alimo 1kg", "850.00", "P", "0.00", "0.00"],
            ["salsicha de frango alimo 340g", "950.00", "P", "0.00", "0.00"],
            ["mortadela de frango predix 1kg", "2800.00", "P", "0.00", "0.00"],
            ["leite nido 15g", "100.00", "P", "0.00", "0.00"],
            ["batata lulu de frango", "500.00", "P", "0.00", "0.00"],
            ["sardinha roch 125g", "700.00", "P", "0.00", "0.00"],
            ["limpa vidro transparente ultra 500ml", "800.00", "P", "0.00", "0.00"],
            ["lava tudo brisa marinha ultra 750ml", "700.00", "P", "0.00", "0.00"],
            ["lava tudo frutos tropicais ultra 750 ml", "700.00", "P", "0.00", "0.00"],
            ["ervilha mista bom apetite 240g", "800.00", "P", "0.00", "0.00"],
            ["biscoitos betna", "50.00", "P", "0.00", "0.00"],
            ["massa espaeguete bom apetite 350g", "400.00", "P", "0.00", "0.00"],
            ["biscoitos de morango 85g", "150.00", "P", "0.00", "0.00"],
            ["massa esparguete b 350g", "350.00", "P", "0.00", "0.00"],
            ["penso lukis", "500.00", "P", "0.00", "0.00"],
            ["biscoito maria samakaka vermelha 65g", "100.00", "P", "0.00", "0.00"],
            ["linguicitas com jindungo sanodia 150g", "950.00", "P", "0.00", "0.00"],
            ["chouriço corrente o melhor da nossa terra", "800.00", "P", "0.00", "0.00"],
            ["vinho tinto j p 750ml", "4900.00", "P", "0.00", "0.00"],
            ["vinho tinto relvas 750ml", "4000.00", "P", "0.00", "0.00"],
            ["vinho tinto folha larga 750ml", "3950.00", "P", "0.00", "0.00"],
            ["vinho tinto pias 750ml", "2800.00", "P", "0.00", "0.00"],
            ["sumo fresh ananas", "100.00", "P", "0.00", "0.00"],
            ["bolo de chocolate dimbo", "150.00", "P", "0.00", "0.00"],
            ["cafe bom dia", "1600.00", "P", "0.00", "0.00"],
            ["cafe cafrica 125g", "1100.00", "P", "0.00", "0.00"],
            ["vinagre de cereal vale verde 500ml", "250.00", "P", "0.00", "0.00"],
            ["refrigerante yala coco ananas 500ml", "300.00", "P", "0.00", "0.00"],
            ["floco de aveia sao pedro 400g", "950.00", "P", "0.00", "0.00"],
            ["salsicha de frango confidence 340g", "1000.00", "P", "0.00", "0.00"],
            ["mortadela de porco pedrix 1kg", "2950.00", "P", "0.00", "0.00"],
            ["massa tomato adal 70 g L", "200.00", "P", "0.00", "0.00"],
            ["pasta de dente colgate triple action 35g", "450.00", "P", "0.00", "0.00"],
            ["biscoito leite amantegado renata 133g", "550.00", "P", "0.00", "0.00"],
            ["detergente so lava 280g", "350.00", "P", "0.00", "0.00"],
            ["creme de bebe para assaduras monami 50ml", "800.00", "P", "0.00", "0.00"],
            ["antisseptico bucal verde special 250ml", "850.00", "P", "0.00", "0.00"],
            ["antisseptico bucal azul special 250ml", "850.00", "P", "0.00", "0.00"],
            ["creme bebe e mama rosa 400ml", "1300.00", "P", "0.00", "0.00"],
            ["creme corporal amor de mae morango 200ml", "850.00", "P", "0.00", "0.00"],
            ["scweppes ginger ale 330ml", "600.00", "P", "0.00", "0.00"],
            ["lava tudo papoite ama 650ml", "500.00", "P", "0.00", "0.00"],
            ["biscoito betna leite", "50.00", "P", "0.00", "0.00"],
            ["detergente ama 25g", "50.00", "P", "0.00", "0.00"],
            ["feijoada", "4500.00", "P", "0.00", "0.00"],
            ["bolo dimbo laranja", "150.00", "P", "0.00", "0.00"],
            ["pipocas com sal 35g", "300.00", "P", "0.00", "0.00"],
            ["corned beef vivo 190g", "1050.00", "P", "0.00", "0.00"],
            ["zipp granadina 400ml", "250.00", "P", "0.00", "0.00"],
            ["biscoitos cacau 85g", "150.00", "P", "0.00", "0.00"],
            ["sumol manga 330ml", "500.00", "P", "0.00", "0.00"],
            ["pasta de dente herbal special 100g", "600.00", "P", "0.00", "0.00"],
            ["pasta de dente tri activo special 100g", "650.00", "P", "0.00", "0.00"],
            ["escova de dente dupla acção special", "250.00", "P", "0.00", "0.00"],
            ["reboçado coca rolo candy", "100.00", "P", "0.00", "0.00"],
            ["emanzena 200g", "800.00", "P", "0.00", "0.00"],
            ["dedorizante cherry blosson lukis 50ml", "1050.00", "P", "0.00", "0.00"],
            ["dedorizante english lavender lukis 50ml", "1050.00", "P", "0.00", "0.00"],
            ["dedorizante black e white nivea 50ml", "2100.00", "P", "0.00", "0.00"],
            ["dedorizante pearl e beauty nivea 50ml", "2100.00", "P", "0.00", "0.00"],
            ["macarao penne adria 350g", "400.00", "P", "0.00", "0.00"],
            ["sardinha karia 125g", "700.00", "P", "0.00", "0.00"],
            ["ketchup kombo 500g", "1500.00", "P", "0.00", "0.00"],
            ["lixivia so lava 1L", "550.00", "P", "0.00", "0.00"],
            ["tempero boa mesa 500ml", "2100.00", "P", "0.00", "0.00"],
            ["margarina 80% alimo 250ml", "750.00", "P", "0.00", "0.00"],
            ["sabonente paixao rosa 125g", "250.00", "P", "0.00", "0.00"],
            ["oleo donna maria 1L", "2600.00", "P", "0.00", "0.00"],
            ["biscoitos sabor ervergreen 85g", "150.00", "P", "0.00", "0.00"],
            ["pomada de sapato preto iude 40ml", "500.00", "P", "0.00", "0.00"],
            ["chourico bom apetite 150g", "1000.00", "P", "0.00", "0.00"],
            ["arroz patriota 4f 1KG", "1150.00", "P", "0.00", "0.00"],
            ["pasta de dente 3em1 special 35g", "350.00", "P", "0.00", "0.00"],
            ["pasta dente carvao vegetal calcident 50g", "350.00", "P", "0.00", "0.00"],
            ["agua bela 1,5L", "200.00", "P", "0.00", "0.00"],
            ["detergente ama 200g", "250.00", "P", "0.00", "0.00"],
            ["red bull 250ml", "1350.00", "P", "0.00", "0.00"],
            ["camera exteerior 2mp ip", "75300.00", "P", "0.00", "0.00"],
            ["nvr peo de 8canais", "371500.00", "P", "0.00", "0.00"],
            ["disco de duro 1tb", "50000.00", "P", "0.00", "0.00"],
            ["cabo utp cat6 305 mt", "65000.00", "P", "0.00", "0.00"],
            ["conetor j45", "250.00", "P", "0.00", "0.00"],
            ["material extra", "190000.00", "P", "0.00", "0.00"],
            ["mao de obra", "150000.00", "P", "0.00", "0.00"],
            ["multswitch poe", "85000.00", "P", "0.00", "0.00"],
            ["kit de 8 cameras ip", "700000.00", "P", "0.00", "0.00"],
            ["atum em pedaço atlantico 185g", "1500.00", "P", "0.00", "0.00"],
            ["biscoito digestivo mini coco", "100.00", "P", "0.00", "0.00"],
            ["sumo tropical cooktail foster clarks 20g", "300.00", "P", "0.00", "0.00"],
            ["sumo de maracuja foster clarks 20g", "300.00", "P", "0.00", "0.00"],
            ["sumo de manga foster clarks 20g", "300.00", "P", "0.00", "0.00"],
            ["sumo cana de maça cana joy 1L", "750.00", "P", "0.00", "0.00"],
            ["pastilha tropical stick", "100.00", "P", "0.00", "0.00"],
            ["pastilha melon stick", "100.00", "P", "0.00", "0.00"],
            ["milby 5frutos", "350.00", "P", "0.00", "0.00"],
            ["refrigerante la vita coco ananas 350ml", "200.00", "P", "0.00", "0.00"],
            ["refrigerante cana joy maça 330ml", "250.00", "P", "0.00", "0.00"],
            ["sumo nutry manga 1L", "1600.00", "P", "0.00", "0.00"],
            ["dedorizante fresh active 48h nivea50ml", "2100.00", "P", "0.00", "0.00"],
            ["parle poppins rebuçada", "100.00", "P", "0.00", "0.00"],
            ["parle mintol rebuçado", "100.00", "P", "0.00", "0.00"],
            ["arroz branco uami 1kg", "1000.00", "P", "0.00", "0.00"],
            ['caderno stilo A5 80 pagnas', '350.00 KZ', "P", 0.00, 0.00],
            ['caderno soccer stars 80pagnas', '200.00 KZ', "P", 0.00, 0.00],
            ['caderno stilo angola 72paginas', '300.00 KZ', "P", 0.00, 0.00],
            ['flocos de aveia alimo 500g', '900.00 KZ', "P", 0.00, 0.00],
            ['batata lisa original lulu 100g', '1,000.00 KZ', "P", 0.00, 0.00],
            ['sardinha 525 125g', '700.00 KZ', "P", 0.00, 0.00],
            ['lampada led avc 15w', '900.00 KZ', "P", 0.00, 0.00],
            ['lampada led avc 10w', '700.00 KZ', "P", 0.00, 0.00],
            ['escova abd', '550.00 KZ', "P", 0.00, 0.00],
            ['escova usa', '300.00 KZ', "P", 0.00, 0.00],
            ['rapf liquido preta', '800.00 KZ', "P", 0.00, 0.00],
            ['biscoito coconut likon 65g', '150.00 KZ', "P", 0.00, 0.00],
            ['biscoito amanteigado coco renata 133g', '500.00 KZ', "P", 0.00, 0.00],
            ['detergente so lava 50g', '100.00 KZ', "P", 0.00, 0.00],
            ['palitos de trigo picante kuyosa 100g', '450.00 KZ', "P", 0.00, 0.00],
            ['lava loiça lavanda ultra 500ml', '650.00 KZ', "P", 0.00, 0.00],
            ['coxa de frango p', '800.00 KZ', "P", 0.00, 0.00],
            ['coxa de frango g', '1,000.00 KZ', "P", 0.00, 0.00],
            ['biscoito chili e tomate serranitas 105g', '300.00 KZ', "P", 0.00, 0.00],
            ['biscoito moça nestle140g', '850.00 KZ', "P", 0.00, 0.00],
            ['biscoito doce de leite bono nestle 90g', '750.00 KZ', "P", 0.00, 0.00],
            ['biscoito de morango bono nestle 90g', '750.00 KZ', "P", 0.00, 0.00],
            ['biscoito limao bono nestle 90g', '750.00 KZ', "P", 0.00, 0.00],
            ['biscoito chocolate bono nestle 90g', '750.00 KZ', "P", 0.00, 0.00],
            ['biscoito de chocolate betna', '50.00 KZ', "P", 0.00, 0.00],
            ['batata fritas de arroz s picante kuyosa 55g', '350.00 KZ', "P", 0.00, 0.00],
            ['batata frita de arroz s bife kuyosa 55g', '350.00 KZ', "P", 0.00, 0.00]
        ];


        // Cabeçalhos
        $cabecalhos = ["Produto", "Preço", "Tipo", "Retenção", "Taxa"];

        // Criar planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Inserir cabeçalhos e dados
        $sheet->fromArray($cabecalhos, null, 'A1');
        $sheet->fromArray($produtos, null, 'A2');

        // Auto dimensionamento das colunas
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Gerar arquivo para download
        $filename = 'relatorio_produtos_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function urgented()
    {
        $user = auth()->user();

        try {
            // Inicia a transação
            DB::beginTransaction();

            $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

            $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
            $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

            $produtos = Produto::whereIn("id", $meus_produtos)->where('entidade_id', $user->entidade_id)->get();
            $lotes_existentes = Lote::where('entidade_id', $user->entidade_id)->get();
            $registros_existentes = Registro::where('entidade_id', $user->entidade_id)->get();
            $estoques_existentes = Estoque::where('entidade_id', $user->entidade_id)->get();


            // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            $loja = Loja::where("status", "activo")
                ->whereIn("id", $minhas_lojas)
                ->where("entidade_id", $user->entidade_id)
                ->first();

            foreach ($lotes_existentes as $item) {
                Lote::withTrashed()->findOrFail($item->id)->forceDelete();
            }

            foreach ($registros_existentes as $item) {
                Registro::withTrashed()->findOrFail($item->id)->forceDelete();
            }

            foreach ($estoques_existentes as $item) {
                Estoque::withTrashed()->findOrFail($item->id)->forceDelete();
            }

            foreach ($produtos as $prd) {

                $verficar_lote = Lote::where('produto_id', $prd->id)->where('entidade_id', $user->entidade_id)->first();

                if (!$verficar_lote) {

                    $lote = Lote::create([
                        'produto_id' => $prd->id,
                        'lote' => substr(str_shuffle(str_repeat($letras, 4)), 0, 4),
                        'status' => "activo",
                        'codigo_barra' => $prd->codigo_barra,
                        'data_validade' => NULL,
                        'data_validade_vitalicio' => 1,
                        'stock_total' => 0,
                        'entidade_id' => $user->entidade_id,
                    ]);
                }

                $verficar_estoques = Estoque::where('produto_id',  $prd->id)->where('entidade_id', $user->entidade_id)->first();

                if (!$verficar_estoques) {

                    $estoque = Estoque::create([
                        "loja_id" => $loja->id,
                        "lote_id" => $lote->id,
                        "produto_id" => $prd->id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" => 10,
                        "operacao" => "Actualizar de Stock",
                        "observacao" => "Entrada inicial de produtos de Stock",
                        'entidade_id' => $user->entidade_id,
                    ]);
                }


                $verficar_estoques = Registro::where('produto_id',  $prd->id)->where('entidade_id', $user->entidade_id)->first();

                if (!$verficar_estoques) {

                    Registro::create([
                        "registro" => "Entrada de Stock",
                        "data_registro" => date('Y-m-d'),
                        "quantidade" => 10,
                        "tipo" => "E",
                        'status' => 'A',
                        "produto_id" => $prd->id,
                        "preco_unitario" => $prd->preco_venda_com_iva,
                        "observacao" => "Entrada inicial de produtos de Stock",
                        "loja_id" => $loja->id,
                        "lote_id" => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $user->entidade_id,
                    ]);
                }
            }
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }


        return redirect()->back();
    }

    public function caixa()
    {
        $user = auth()->user();

        try {
            // Inicia a transação
            DB::beginTransaction();

            $produtos = Caixa::where('entidade_id', $user->entidade_id)
                ->where('status_admin', 'liberado')->get();

            foreach ($produtos as $item) {
                $upd = Caixa::findOrFail($item->id);
                $upd->status = "fechado";
                $upd->active = false;
                $upd->user_open_id = NULL;
                $upd->user_close_id = Auth::user()->id;
                $upd->continuar_apos_login = false;
                $upd->update();
            }

            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        return redirect()->back();
    }

    public function admin()
    {
        $user = auth()->user();

        if ($user->level  == 2) {
            $entidade = Entidade::whereIn('level', [2])->count();
        } else if ($user->level == 3) {
            $entidade = Entidade::whereIn('level', [1, 2, 3])->count();
        }

        $anuncios_total = AnuncioAdmin::count();
        $membros_total = Membro::count();

        $head = [
            "titulo" => "Dashboard",
            "descricao" => env('APP_NAME'),
            "entidade_total" => $entidade,
            "anuncios_total" => $anuncios_total,
            "membros_total" => $membros_total,
        ];

        return view('admin.dashboard', $head);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function painel_escolha()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Dashboard Painel",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.dashboard-painel', $head);
    }

    public function gerar_licenca_configuracao()
    {
        $head = [
            "titulo" => "Gerar Licenças",
            "descricao" => env('APP_NAME'),
            "codigo" => null,
        ];

        return view('admin.configuracao.licenca', $head);
    }

    public function gerar_licenca_configuracao_post(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required',
            'dia_tempo' => 'required',
            'data_final' => 'required'
        ]);

        $head = [
            "titulo" => "Gerar Licenças",
            "descricao" => env('APP_NAME'),
            "codigo" => Crypt::encrypt($request->all()),
        ];

        return response()->json(['message' => "Conta bancária(TPA) desactivo com sucesso.!", 'codigo' => Crypt::encrypt($request->all())], 200);
    }

    public function configuracao()
    {
        $configuracao = Configuracao::first();

        $head = [
            "titulo" => "Configuração",
            "descricao" => env('APP_NAME'),
            "configuracao" => $configuracao
        ];

        return view('admin.configuracao.create', $head);
    }

    public function configuracao_post(Request $request)
    {

        try {
            DB::beginTransaction();
            $configuracao = Configuracao::first();

            if ($configuracao) {
                $update = Configuracao::findOrFail($configuracao->id);
                $update->limite_dias = $request->dias;
                $update->valor_cota = $request->valor_cota;
                $update->dia_limite_pagamento = $request->dia_limite_pagamento;
                $update->juros_diario = $request->juros_diario;
                $update->multa_percentual = $request->multa_percentual;
                $update->update();
            } else {
                $create = Configuracao::create([
                    "limite_dias" => $request->dias,
                    "valor_cota" => $request->valor_cota,
                    "dia_limite_pagamento" => $request->dia_limite_pagamento,
                    "juros_diario" => $request->juros_diario,
                    "multa_percentual" => $request->multa_percentual,
                ]);

                $create->save();
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        $verificar_backup = BackupSetting::where('entidade_id', $entidade->id)->first();

        if (!$verificar_backup) {
            BackupSetting::create([
                'user_id' => $user->id,
                'folder_path' => null,
                'enabled' => 0,
                'retain' => 24,
                'frequency_minutes' => 120,
                'last_run_at' => null,
                'tipo_mysql' => "padrao",
                'entidade_id' => $entidade->empresa->id
            ]);
        }

        if ($entidade->empresa->tipo_entidade->sigla === 'CFAT') {
            if ($user->can('diretamente ao pronto de venda') && ($user->can('Operador') ||  $user->can('operador'))) {
                if ($entidade->empresa->tipo_pronto_venda == "Grelha") {
                    return redirect()->route('pronto-venda');
                } else {
                    return redirect()->route('pos.index');
                }
            }
        }

        $head = [
            "titulo" => "Dashboard",
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.home', $head);
    }

    public function configuracao_operacoes()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($entidade->empresa->tipo_entidade->sigla != 'SEGPRIVADA') {
            if ($user->can('diretamente ao pronto de venda') && ($user->can('Operador') ||  $user->can('operador'))) {
                if ($entidade->empresa->tipo_pronto_venda == "Grelha") {
                    return redirect()->route('pronto-venda');
                } else {
                    return redirect()->route('pos.index');
                }
            }
        }


        $caixas = Caixa::where('active', false)->where('status_admin', 'liberado')->where('status', 'fechado')->where('entidade_id', $entidade->empresa->id)->get();
        $bancos = ContaBancaria::where('active', false)->where('status_admin', 'liberado')->where('status', 'fechado')->where('entidade_id', $entidade->empresa->id)->get();

        $caixaActivo = Caixa::where('active', true)
            ->where('status', 'aberto')
            ->where('user_open_id', Auth::user()->id)
            ->where('status_admin', 'liberado')
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        $bancoActivo = ContaBancaria::where('active', true)
            ->where('user_open_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        $head = [
            "titulo" => __('messages.configuracoes'),
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "bancos" => $bancos,
            "caixaActivo" => $caixaActivo,
            "bancoActivo" => $bancoActivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.configuracao', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_destino_pedidos(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('configuracoes')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $empresa = Entidade::findOrFail($entidade->empresa->id);

            $status = "";
            if ($empresa->destino_pedidos == "Normal") {
                $status = "Cuzinha";
            }

            if ($empresa->destino_pedidos == "Cuzinha") {
                $status = "Normal";
            }

            $empresa->destino_pedidos = $status;
            $empresa->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "operação realizada com sucesso!"], 200);
    }

    public function configuracao_inicializacao()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $entid = Entidade::findOrFail($entidade->empresa->id);

        $status = "";

        if ($entid->inicializacao == "N") {
            $status = "Y";
        } else if ($entid->inicializacao == "Y") {
            $status = "N";
        }

        $entid->inicializacao = $status;
        $entid->update();

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
    }

    public function configuracao_regularizar_factura()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $ultimoRecibo = Venda::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();

        if ($ultimoRecibo) {
            // Subtrai 1 hora do created_at
            $ultimoRecibo->created_at = Carbon::parse($ultimoRecibo->created_at)->subHour();

            // Salva a alteração no banco de dados
            $ultimoRecibo->save();
        }

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
    }

    public function configuracao_urgentes()
    {
        //
        ini_set('max_execution_time', 300); // 5 minutos
        ini_set('memory_limit', '2024M');  // Ajuste para 1024 MB ou outro valor

        $user = auth()->user();

        $entidade = User::with('empresa')->findOrFail(Auth::id());

        $produtos = Produto::where('entidade_id', $entidade->empresa->id)->get();

        foreach ($produtos as $produto) {
            if (is_numeric($produto->preco_venda) && is_numeric($produto->taxa)) {
                $produto->preco_venda_com_iva = $produto->preco_venda + ($produto->preco_venda * ($produto->taxa / 100));
                $produto->update();
            }
        }

        $pagamentos = Venda::where('entidade_id', Auth::user()->entidade_id)
            ->select('id', 'valor_total', 'lucro_iva_total', 'lucro_total', 'custo_total', 'desconto', 'total_iva', 'total_incidencia', 'quantidade')
            ->where('factura', 'FR')
            ->get();
        foreach ($pagamentos as $item) {

            $DESCONTO_APLICADO = 0;

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalPagar = 0;
            $totalDesconto = 0;
            $totalCusto = 0;
            $totalLucro = 0;
            $totalLucroIva = 0;

            $pag = Venda::select('id', 'valor_total', 'lucro_iva_total', 'lucro_total', 'custo_total', 'desconto', 'total_iva', 'total_incidencia', 'quantidade')
                ->findOrFail($item->id);

            $items = ItemVenda::where('factura_id', $item->id)
                ->where('entidade_id', Auth::user()->entidade_id)
                ->get();


            foreach ($items as $it) {

                $produto = Produto::findOrFail($it->produto_id);

                // 1. proço X quantidade
                $_VALOR_PAGAR = $produto->preco_venda_com_iva * $it->quantidade;

                $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);

                $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

                $_VALOR_IVA = $_VALOR_BASE * ($produto->taxa / 100);

                $_VALOR_RETENCAO = 0;

                if ($produto->tipo == "S") {
                    if ($produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                        $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);
                    }
                } else {
                    $_VALOR_RETENCAO = 0;
                }

                $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;

                $_CUSTO = $produto->preco_custo * $it->quantidade;
                $_LUCRO = (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $it->quantidade;
                $_LUCRO_IVA = (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $it->quantidade;

                $detalhe = ItemVenda::findOrFail($it->id);
                $detalhe->valor_pagar = $_VALOR_TOTAL;
                $detalhe->total = $_VALOR_TOTAL;
                $detalhe->retencao_fonte = $_VALOR_RETENCAO;
                $detalhe->preco_unitario = $produto->preco_venda_com_iva - $_DESCONTO;
                $detalhe->custo = $_CUSTO;
                $detalhe->lucro = $_LUCRO;
                $detalhe->lucro_iva = $_LUCRO_IVA;
                $detalhe->desconto_aplicado = $DESCONTO_APLICADO;
                $detalhe->valor_base = $_VALOR_BASE;
                $detalhe->valor_iva = $_VALOR_IVA;
                $detalhe->desconto_aplicado_valor = $_DESCONTO;
                $detalhe->update();

                $totalValorBase += $detalhe->valor_base;
                $totalValorIva += $detalhe->valor_iva;
                $totalPagar += $detalhe->total;
                $totalDesconto += $detalhe->desconto_aplicado_valor;
                $totalCusto += $detalhe->custo;
                $totalLucro += $detalhe->lucro;
                $totalLucroIva += $detalhe->lucro_iva;
            }

            $pag->valor_total = $totalPagar - $totalDesconto;
            $pag->lucro_iva_total = $totalLucroIva;
            $pag->lucro_total = $totalLucro;
            $pag->custo_total = $totalCusto;
            $pag->desconto = $totalDesconto;

            $pag->total_iva = $totalValorIva;
            $pag->total_incidencia = $totalValorBase;
            $pag->quantidade = $totalItems;
            $pag->save();
        }


        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
    }

    public function configuracao_finalizacao()
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $entid = Entidade::findOrFail($entidade->empresa->id);

        $status = "";

        if ($entid->finalizacao == "N") {
            $status = "Y";
        } else if ($entid->finalizacao == "Y") {
            $status = "N";
        }

        $entid->finalizacao = $status;
        $entid->update();

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");
    }

    // DASHBOARD PRINCIPAL
    public function dashboardPrincipal(Request $request)
    {
        $user = auth()->user();

        $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        if ($entidade->empresa->tipo_entidade->sigla != 'SEGPRIVADA') {
            if ($user->can('diretamente ao pronto de venda') && ($user->can('Operador') ||  $user->can('operador'))) {
                if ($entidade->empresa->tipo_pronto_venda == "Grelha") {
                    return redirect()->route('pronto-venda');
                } else {
                    return redirect()->route('pos.index');
                }
            }
        }

        if ($entidade->empresa->tipo_entidade->sigla == 'HOSP') {
            if ($user->can('monitoramento central atendimento') || $user->can('monitoramento enfermagem triagem') || $user->can('monitoramento laboratorio') || $user->can('monitoramento consultorio')) {
                return redirect()->route('dashboard-hospital');
            }
        }

        // hoje (00:00 -> 23:59:59)
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        if ($entidade->empresa->tipo_entidade->sigla == 'HOSP') {
            Consulta::where('status', 'AGENDADA')->whereDate('data_consulta', '<', $startOfDay)->where('entidade_id', $entidade->empresa->id)->update(['status' => 'ATRASADA', 'updated_at' => now()]);
            Exame::where('status', 'AGENDADA')->whereDate('data_exame', '<', $startOfDay)->where('entidade_id', $entidade->empresa->id)->update(['status' => 'ATRASADA', 'updated_at' => now()]);
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $data = Carbon::now()->subDays(30)->toDateString(); // Data dos últimos 5 dias

        $produtos = Produto::when($request->nome_referencia, function ($query, $value) {
            $query->where("nome", "LIKE", "%{$value}%");
            $query->orWhere("referencia", "LIKE", "%{$value}%");
        })
            ->when($request->categoria_id, function ($query, $value) {
                $query->where("categoria_id", $value);
            })
            ->when($request->marca_id, function ($query, $value) {
                $query->where("marca_id", $value);
            })
            ->withSum("quantidade", "quantidade")
            ->where("entidade_id", $entidade->empresa->id)
            ->where("tipo", "P")
            ->having("quantidade_sum_quantidade", ">=", 20) // Adicionando a condição de quantidade máxima permitida (<= 50)
            ->orderBy("nome", "asc")
            ->limit(5)
            ->get();

        $total_vendas = Venda::where("entidade_id", $entidade->empresa->id)->whereIn("status_factura", ["pago"])->sum("valor_total");

        $vendas = Venda::select(
            DB::raw("SUM(valor_total) as total_vendas"),
            DB::raw("SUM(quantidade) as total_quantidade")
        )
            ->where("entidade_id", $entidade->empresa->id)
            ->whereIn("status_factura", ["pago"])
            ->first();

        $total_estoque_activo = Lote::join("registros", "lotes_validade_produtos.id", "=", "registros.lote_id")
            ->where("registros.entidade_id", $entidade->empresa->id)
            ->where("lotes_validade_produtos.status", "activo")
            ->selectRaw("SUM(CASE WHEN registros.tipo = 'E' THEN registros.quantidade ELSE 0 END) - SUM(CASE WHEN registros.tipo = 'S' THEN registros.quantidade ELSE 0 END) as total_estoque")
            ->first();

        $total_estoque_expirado = Lote::join("registros", "lotes_validade_produtos.id", "=", "registros.lote_id")
            ->where("lotes_validade_produtos.status", "expirado")
            ->where("registros.entidade_id", $entidade->empresa->id)
            ->selectRaw("SUM(CASE WHEN registros.tipo = 'E' THEN registros.quantidade ELSE 0 END) - SUM(CASE WHEN registros.tipo = 'S' THEN registros.quantidade ELSE 0 END) as total_estoque")
            ->first();


        // Obter o total de agendamentos por status
        $totalCancelados = Agendamento::where("entidade_id", $entidade->empresa->id)
            ->where("status", "cancelado")
            ->count();

        $totalExpirados = Agendamento::where("entidade_id", $entidade->empresa->id)
            ->where("status", "expirado")
            ->count();

        $totalAtendidos = Agendamento::where("entidade_id", $entidade->empresa->id)
            ->where("status", "atendido")
            ->count();

        $totalPendentes = Agendamento::where("entidade_id", $entidade->empresa->id)
            ->where("status", "pendente")
            ->count();


        $totalCursos = Curso::where("entidade_id", $entidade->empresa->id)->count();
        $totalTurmas = Turma::where("entidade_id", $entidade->empresa->id)->count();
        $totalTurnos = Turno::where("entidade_id", $entidade->empresa->id)->count();

        $totalAlunos = Cliente::where("entidade_id", $entidade->empresa->id)->count();
        $totalFormador = Funcionario::where("entidade_id", $entidade->empresa->id)->count();

        $totalSolicitacao = Documento::where("entidade_id", $entidade->empresa->id)->count();
        $totalSalas = Sala::where("entidade_id", $entidade->empresa->id)->count();
        $totalMesas = Mesa::where("entidade_id", $entidade->empresa->id)->count();
        $totalMesasOcupadas = Mesa::where("entidade_id", $entidade->empresa->id)->where('solicitar_ocupacao', 'OCUPADA')->count();
        $totalAnoLectivo = AnoLectivo::where("entidade_id", $entidade->empresa->id)->count();
        $totalQuarto = Quarto::where("entidade_id", $entidade->empresa->id)->count();
        $totalCliente = Cliente::where("entidade_id", $entidade->empresa->id)->count();

        $totalClienteContratos = 0;
        $totalOcorrencias = 0;
        $totalPostos = 0;

        $hoje = Carbon::today()->toDateString(); // formato YYYY-MM-DD

        $totalReservas = Reserva::where("entidade_id", $entidade->empresa->id)->count();

        $totalReservasCheckOut = Reserva::where("data_final", "=", date("Y-m-d"))
            ->whereIn("status", ["SUCESSO", "EM USO"])
            ->where("entidade_id", $entidade->empresa->id)
            ->count();

        $totalReservasCheckIn = Reserva::whereIn("status", ["PENDENTE", "EM USO"])
            ->where("data_inicio", "=", date("Y-m-d"))
            ->where("entidade_id", $entidade->empresa->id)
            ->count();

        $totalReservasFeitasHoje = Reserva::whereDate("created_at", $hoje)
            ->where("entidade_id", $entidade->empresa->id)
            ->count();

        $reservasEmUso = Reserva::where("status", "EM USO")
            ->where("entidade_id", $entidade->empresa->id)
            ->count();

        $totalTarifarios = Produto::where("aplicado", "Y")->where("entidade_id", $entidade->empresa->id)->count();


        // PRODUCAO FABRIL
        $productions = Producao::where("entidade_id", $entidade->empresa->id)->count();

        $produtos_primas = Produto::where("entidade_id", $entidade->empresa->id)->where("tipo", "P")->where("tipo_stock", "P")->count();

        $totalBread = Producao::where("entidade_id", $entidade->empresa->id)->sum('quantidade_estimada');
        $today = Producao::where("entidade_id", $entidade->empresa->id)->whereDate('created_at', today())->count();

        $hoje = now()->toDateString();

        $producaoHoje = Producao::whereDate('created_at', $hoje)->where('entidade_id', $entidade->empresa->id)->sum('quantidade_produzida');
        $producaoMes = Producao::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('entidade_id', $entidade->empresa->id)->sum('quantidade_produzida');
        $ordensExecucao = Producao::whereIn('status', [
            'PENDENTE',
            'EM_PRODUCAO'
        ])->where('entidade_id', $entidade->empresa->id)->count();

        $perdas = Producao::whereMonth('created_at', now()->month)->where('entidade_id', $entidade->empresa->id)->sum('quantidade_perdida');

        $eficiencia = Producao::whereMonth('created_at', now()->month)->where('entidade_id', $entidade->empresa->id)->selectRaw('SUM(quantidade_produzida) as produzida, SUM(quantidade_estimada) as estimada')->first();

        $percentual = 0;

        if ($eficiencia->estimada > 0) {
            $percentual = round(($eficiencia->produzida / $eficiencia->estimada) * 100, 2);
        }


        $head = [
            "titulo" => "Dashboard Principal",
            "descricao" => env("APP_NAME"),
            "empresa" => $entidade,
            "produtos" => $produtos,
            "lojas" => Loja::where("entidade_id", $entidade->empresa->id)->get(),
            "total_produtos" => Produto::where("entidade_id", $entidade->empresa->id)->where("tipo", "P")->count(),
            "total_servicos" => Produto::where("entidade_id", $entidade->empresa->id)->where("tipo", "S")->count(),
            "total_estoque_activo" => $total_estoque_activo->total_estoque,
            "total_estoque_expirado" => $total_estoque_expirado->total_estoque,
            "total_vendas" => $total_vendas,
            "vendas" => $vendas,

            "total_cancelados" => $totalCancelados,
            "total_expirados" => $totalExpirados,
            "total_atendidos" => $totalAtendidos,
            "total_pendentes" => $totalPendentes,

            "total_cursos" => $totalCursos,
            "total_solicitacao" => $totalSolicitacao,
            "total_turmas" => $totalTurmas,
            "total_turnos" => $totalTurnos,
            "total_alunos" => $totalAlunos,
            "total_salas" => $totalSalas,
            "totalQuarto" => $totalQuarto,
            "totalMesas" => $totalMesas,
            "totalMesasOcupadas" => $totalMesasOcupadas,
            "total_anos_lectivos" => $totalAnoLectivo,
            "total_formadores" => $totalFormador,
            "totalCliente" => $totalCliente,
            "totalClienteContratos" => $totalClienteContratos,
            "totalOcorrencias" => $totalOcorrencias,
            "totalPostos" => $totalPostos,

            "totalReservas" => $totalReservas,
            "totalReservasFeitasHoje" => $totalReservasFeitasHoje,
            "totalReservasCheckOut" => $totalReservasCheckOut,
            "totalReservasCheckIn" => $totalReservasCheckIn,
            "totalTarifarios" => $totalTarifarios,
            "reservasEmUso" => $reservasEmUso,

            // PRODUCAO FABRIL
            "productions" => $productions,
            "today" => $today,
            "produtos_primas" => $produtos_primas,
            "totalBread" => $totalBread,

            "producaoHoje" => $producaoHoje,
            "producaoMes" => $producaoMes,
            "ordensExecucao" => $ordensExecucao,
            "perdas" => $perdas,
            "percentual" => $percentual,

            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade.modulos"])->findOrFail(Auth::user()->id),
        ];


        return view("dashboard.dashboard", $head);
    }

    // DASHBOARD HOSPITAL
    public function dashboardHospital(Request $request)
    {
        $user = auth()->user();

        $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

        // hoje (00:00 -> 23:59:59)
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        if ($entidade->empresa->tipo_entidade->sigla == 'HOSP') {
            Consulta::where('status', 'AGENDADA')->whereDate('data_consulta', '<', $startOfDay)->where('entidade_id', $entidade->empresa->id)->update(['status' => 'ATRASADA', 'updated_at' => now()]);
            Exame::where('status', 'AGENDADA')->whereDate('data_exame', '<', $startOfDay)->where('entidade_id', $entidade->empresa->id)->update(['status' => 'ATRASADA', 'updated_at' => now()]);
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $totalCliente = Cliente::where("entidade_id", $entidade->empresa->id)->count();
        $totalQuarto = Quarto::where("entidade_id", $entidade->empresa->id)->count();
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $totalConsultaAgendadasHoje = Consulta::where('status', 'AGENDADA')->where('entidade_id', $entidade->empresa->id)->whereBetween('data_consulta', [$startOfDay, $endOfDay])->count();
        // contar consultas na semana
        $totalConsultaAgendadasSemana = Consulta::where('status', 'AGENDADA')->where('entidade_id', $entidade->empresa->id)->whereBetween('data_consulta', [$startOfWeek, $endOfWeek])->count();
        // contar Exames agendadas (status = 'agendada') para hoje
        $totalExameAgendadasHoje = Exame::where('status', 'AGENDADA')->where('entidade_id', $entidade->empresa->id)->whereBetween('data_exame', [$startOfDay, $endOfDay])->count();
        // contar Exames na semana
        $totalExameAgendadasSemana = Exame::where('status', 'AGENDADA')->where('entidade_id', $entidade->empresa->id)->whereBetween('data_exame', [$startOfWeek, $endOfWeek])->count();

        // Consultas atrasadas (não realizadas)
        $totalConsultaAtrazadas = Consulta::where('status', 'ATRASADA')->where('entidade_id', $entidade->empresa->id)->count();
        // Exames atrasados (não realizados)
        $totalExameAtrazadas = Exame::where('status', 'ATRASADA')->where('entidade_id', $entidade->empresa->id)->count();

        $totalMedico = Medico::where("tipo", ["Medico"])->where("entidade_id", $entidade->empresa->id)->count();
        $totalEnfermeiro = Medico::where("tipo", ["Enfermeiro"])->where("entidade_id", $entidade->empresa->id)->count();
        $totalTecnio = Medico::where("tipo", ["Tecnico"])->where("entidade_id", $entidade->empresa->id)->count();
        $totalExame = Exame::where("entidade_id", $entidade->empresa->id)->count();
        $totalConsulta = Consulta::where("entidade_id", $entidade->empresa->id)->count();
        $totalTriagem = FichaTriagem::where("entidade_id", $entidade->empresa->id)->count();
        $totalInternamento = Internamento::where("entidade_id", $entidade->empresa->id)->count();

        $totalObito = Obito::where("entidade_id", $entidade->empresa->id)->count();
        $totalMorgue = Morgue::where("entidade_id", $entidade->empresa->id)->count();

        $total_atendimentos = Atendimento::where('entidade_id', $entidade->empresa->id)->count();
        $total_plano_tratamento = PlanoTratamento::where('entidade_id', $entidade->empresa->id)->count();

        $total_resultados_exames = ResultadoExame::where('entidade_id', $entidade->empresa->id)->count();

        $head = [
            "titulo" => "Dashboard Principal",
            "descricao" => env("APP_NAME"),
            "empresa" => $entidade,
            "total_resultados_exames" => $total_resultados_exames,
            "total_plano_tratamento" => $total_plano_tratamento,
            "totalCliente" => $totalCliente,
            "totalObito" => $totalObito,
            "totalMorgue" => $totalMorgue,
            "totalQuarto" => $totalQuarto,

            "totalMedico" => $totalMedico,
            "totalEnfermeiro" => $totalEnfermeiro,
            "totalTecnio" => $totalTecnio,
            "totalExame" => $totalExame,
            "totalConsulta" => $totalConsulta,
            "totalAtendimentos" => $total_atendimentos,
            "totalTriagem" => $totalTriagem,
            "totalInternamento" => $totalInternamento,
            "totalConsultaAgendadasHoje" => $totalConsultaAgendadasHoje,
            "totalConsultaAgendadasSemana" => $totalConsultaAgendadasSemana,
            "totalExameAgendadasHoje" => $totalExameAgendadasHoje,
            "totalExameAgendadasSemana" => $totalExameAgendadasSemana,
            "totalConsultaAtrazadas" => $totalConsultaAtrazadas,
            "totalExameAtrazadas" => $totalExameAtrazadas,

            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade.modulos"])->findOrFail(Auth::user()->id),
        ];


        return view("dashboard.dashboard-hospitalar", $head);
    }

    // DASHBOARD RECURSOS HUMANOS
    public function dashboardRecursoHumano(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('painel recursos humano')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $total_receita = Venda::where('conta_movimento', 'receita')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        $total_dispesa = Venda::where('conta_movimento', 'dispesa')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');

        $total_funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->count();
        $total_departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->count();
        $total_cargos = Cargo::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos_renovados = Contrato::where('renovacoes_efectuadas', '!=', 0)->where('entidade_id', $entidade->empresa->id)->count();
        $total_pacotes = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_saidas = MotivoSaida::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_ausencias = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->count();

        $head = [
            "titulo" => "Dashboard Recursos Humanos",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "total_receita" => $total_receita,
            "total_dispesa" => $total_dispesa,

            "total_funcionarios" => $total_funcionarios,
            "total_departamentos" => $total_departamentos,
            "total_motivos_saidas" => $total_motivos_saidas,
            "total_motivos_ausencias" => $total_motivos_ausencias,
            "total_cargos" => $total_cargos,
            "total_contratos" => $total_contratos,
            "total_contratos_renovados" => $total_contratos_renovados,
            "total_pacotes" => $total_pacotes,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.recursos-humanos', $head);
    }

    // CONFIGURCAO RECURSOS HUMANOS
    public function configuracaoRecursoHumano(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('painel financeiro')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $total_receita = Venda::where('conta_movimento', 'receita')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');
        $total_dispesa = Venda::where('conta_movimento', 'dispesa')->where('entidade_id', $entidade->empresa->id)->where('status_factura', 'pago')->sum('valor_total');

        $total_funcionarios = Funcionario::where('entidade_id', $entidade->empresa->id)->count();
        $total_departamentos = Departamento::where('entidade_id', $entidade->empresa->id)->count();
        $total_cargos = Cargo::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos = Contrato::where('entidade_id', $entidade->empresa->id)->count();
        $total_contratos_renovados = Contrato::where('renovacoes_efectuadas', '!=', 0)->where('entidade_id', $entidade->empresa->id)->count();
        $total_pacotes = PacoteSalarial::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_saidas = MotivoSaida::where('entidade_id', $entidade->empresa->id)->count();
        $total_motivos_ausencias = MotivoAusencia::where('entidade_id', $entidade->empresa->id)->count();

        $head = [
            "titulo" => "Dashboard Recursos Humanos",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "total_receita" => $total_receita,
            "total_dispesa" => $total_dispesa,

            "total_funcionarios" => $total_funcionarios,
            "total_departamentos" => $total_departamentos,
            "total_motivos_saidas" => $total_motivos_saidas,
            "total_motivos_ausencias" => $total_motivos_ausencias,
            "total_cargos" => $total_cargos,
            "total_contratos" => $total_contratos,
            "total_contratos_renovados" => $total_contratos_renovados,
            "total_pacotes" => $total_pacotes,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.configuracao-recursos-humanos', $head);
    }

    // DASHBOARD LOGISTOCA
    public function dashboardLogistica(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('painel financeiro')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $total_encomendas = EncomendaFornecedore::where('entidade_id', $entidade->empresa->id)->count();
        $total_fornecedores = Fornecedore::where('entidade_id', $entidade->empresa->id)->count();
        $total_produtos = Produto::where('tipo', 'P')->where('entidade_id', $entidade->empresa->id)->count();
        $total_requisicoes = Requisicao::where('entidade_id', $entidade->empresa->id)->count();


        $head = [
            "titulo" => "Dashboard Logística",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,

            "total_encomendas" => $total_encomendas,
            "total_fornecedores" => $total_fornecedores,
            "total_produtos" => $total_produtos,
            "total_requisicoes" => $total_requisicoes,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.logistica', $head);
    }

    public function giroProdutos()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // ->where('vendas.entidade_id', $entidade->empresa->id)

        $produtos = Produto::with(['registros' => function ($q) {
            $q->where('tipo', 'S');
        }])->where('entidade_id', $entidade->empresa->id)->get();

        $dados = $produtos->map(function ($produto) {
            $totalSaidas = $produto->registros->sum('quantidade');
            return [
                'nome' => $produto->nome,
                'total_saidas' => $totalSaidas,
            ];
        });


        $altoGiro = $dados->filter(fn($p) => $p['total_saidas'] >= 5)->values();
        $baixoGiro = $dados->filter(fn($p) => $p['total_saidas'] < 5)->values();

        return response()->json([
            'altoGiro' => $altoGiro,
            'baixoGiro' => $baixoGiro,
        ]);
    }

    // DASHBOARD FINANCEIRO
    public function dashboardFinanceiro(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('painel financeiro')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $contasReceberAtraso = Venda::whereIn('factura', ['FT'])
            ->whereIn('factura_divida', ['Y'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('data_vencimento', '<', now()->startOfMonth())
            ->sum('valor_divida');


        $contasReceberMes = Venda::whereIn('factura', ['FT'])
            ->whereIn('factura_divida', ['Y'])
            ->where('entidade_id', $entidade->empresa->id)
            ->whereMonth('data_vencimento', now()->month)
            ->whereYear('data_vencimento', now()->year)
            ->sum('valor_divida');

        // Contas a pagar deste meses
        $contasPagarAtraso = FacturaEncomendaFornecedor::where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->where('data_vencimento', '<', now()->startOfMonth())
            ->sum('valor_divida');

        // contas a pagar dos meses passados
        $contasPagarMes = FacturaEncomendaFornecedor::where('status', false)
            ->where('entidade_id', $entidade->empresa->id)
            ->whereMonth('data_vencimento', now()->month)
            ->whereYear('data_vencimento', now()->year)
            ->sum('valor_divida');

        // Saldo atual
        $receitasPagas = OperacaoFinanceiro::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');
        $despesasPagas = OperacaoFinanceiro::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->where('status_pagamento', 'pago')->sum('motante');

        $saldoAtual = $receitasPagas - $despesasPagas;

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)->where('status_admin', 'liberado')
            ->where('status_admin', 'liberado')->pluck('subconta_id');
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->pluck('subconta_id');

        $saldos_bancos = OperacaoFinanceiro::whereIn('subconta_id', $bancos)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita_caixa,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa_caixa
            ")
            ->first();


        $saldos_caixas = OperacaoFinanceiro::whereIn('subconta_id', $caixas)
            ->where('status_pagamento', 'pago')
            ->where('entidade_id', $entidade->empresa->id)
            ->selectRaw("
                SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita_caixa,
                SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa_caixa
            ")
            ->first();


        $head = [
            "titulo" => "Dashboard Financeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "contasReceberAtraso" => $contasReceberAtraso,
            "contasReceberMes" => $contasReceberMes,
            "contasPagarAtraso" => $contasPagarAtraso,
            "contasPagarMes" => $contasPagarMes,
            "receitasPagas" => $receitasPagas,
            "despesasPagas" => $despesasPagas,
            "saldoAtual" => $saldoAtual,

            "saldos_bancos" => $saldos_bancos,
            "saldos_caixas" => $saldos_caixas,

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.financeiro', $head);
    }

    public function lucrosMensais()
    {
        $meses = range(1, 12);
        $ano = now()->year;
        $dados = [];

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        foreach ($meses as $mes) {
            $receita = DB::table('vendas')
                ->whereYear('data_emissao', $ano)
                ->whereMonth('data_emissao', $mes)
                ->whereIn('factura', ['FR'])
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('valor_total');

            $receita2 = DB::table('operacoes_financeiras')
                ->whereYear('date_at', $ano)
                ->whereMonth('date_at', $mes)
                ->where('type', 'D')
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('motante');

            $custo = DB::table('vendas')
                ->whereYear('data_emissao', $ano)
                ->whereMonth('data_emissao', $mes)
                ->whereIn('factura', ['FR'])
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('custo_total');

            $despesas = DB::table('operacoes_financeiras')
                ->whereYear('date_at', $ano)
                ->whereMonth('date_at', $mes)
                ->where('type', 'R')
                ->where('entidade_id', $entidade->empresa->id)
                ->sum('motante');

            $lucroBruto = ($receita + $receita2) - $custo;
            $lucroLiquido = $lucroBruto - $despesas;

            $dados[] = [
                'mes' => Carbon::create()->month($mes)->format('M'),
                'lucro_bruto' => $lucroBruto,
                'lucro_liquido' => $lucroLiquido,
            ];
        }

        return response()->json($dados);
    }

    // DASHBOARD FINANCEIRO
    public function alertas(Request $request)
    {
        $user = auth()->user();

        $hoje = Carbon::now();
        $umMes = $hoje->copy()->addMonth();
        $doisMeses = $hoje->copy()->addMonths(2);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $alertas = ClienteContrato::with(['cliente'])
            ->whereBetween('data_final', [$hoje, $doisMeses])
            ->where('entidade_id', $entidade->empresa->id)
            ->get()
            ->map(function ($contrato) use ($hoje, $umMes, $doisMeses) {
                $status = null;
                $dataFim = \Carbon\Carbon::parse($contrato->data_final);
                if ($contrato->data_final <= $umMes) {
                    $status = 'vence em 1 mês';
                } elseif ($contrato->data_final <= $doisMeses) {
                    $status = 'vence em 2 meses';
                }
                return [
                    'id' => $contrato->id,
                    'codigo_contrato' => $contrato->codigo_contrato,
                    'cliente' => $contrato->cliente->nome ?? 'N/A',
                    'data_final' => $dataFim->format('d/m/Y'),
                    'status' => $status
                ];
            });

        return response()->json($alertas);
    }

    // DASHBOARD FINANCEIRO
    public function taxa_irt(Request $request)
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $taxas = TaxaIRT::paginate(14);

        $head = [
            "titulo" => "Dashboard Financeiro",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "taxas" => $taxas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];


        return view('dashboard.taxas_irt', $head);
    }


    public function graficoVendas(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        // Usa data atual se nenhuma data for passada
        $dataFinal = $request->input('data') ?? now()->toDateString();
        $dataInicio = Carbon::parse($dataFinal)->subDays(14)->toDateString();

        $vendas = DB::table('vendas')
            ->join('itens_vendas', 'itens_vendas.factura_id', '=', 'vendas.id')
            ->whereBetween('vendas.data_emissao', [$dataInicio, $dataFinal])
            ->where('vendas.entidade_id', $entidade->empresa->id)
            ->select(
                DB::raw('DATE(vendas.data_emissao) as dia'),
                DB::raw('SUM(itens_vendas.preco_unitario * itens_vendas.quantidade) as total')
            )
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        return response()->json([
            'labels' => $vendas->pluck('dia'),
            'valores' => $vendas->pluck('total'),
        ]);
    }

    public function exportarRelatorio(Request $request)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $dataFinal = $request->data ? Carbon::parse($request->data) : now();
        $dataInicial = $dataFinal->copy()->subDays(14);

        $dados = DB::table('vendas')
            ->select(DB::raw('DATE(data_emissao) as dia'), DB::raw('SUM(valor_total) as total'))
            ->whereBetween('data_emissao', [$dataInicial->toDateString(), $dataFinal->toDateString()])
            ->where('entidade_id', $entidade->empresa->id)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $totalGeral = $dados->sum('total');

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Relatório de Faturamento dos ultimos 15 dias",
            "descricao" => env("APP_NAME"),
            "dados" => $dados,
            "total" => $totalGeral,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "dataInicial" => $dataInicial,
            "dataFinal" => $dataFinal,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.relatorios.relatorio-faturamento', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function graficoComparativo(Request $request)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $anoAtual = now()->year;

        $dados = DB::table('vendas')
            ->select(
                DB::raw('MONTH(data_emissao) as mes'),
                DB::raw('SUM(valor_total) as faturamento'),
                DB::raw('COUNT(*) as pedidos'),
                DB::raw('SUM(lucro_total) as lucro') // ou total - custo, se tiver
            )
            ->where('entidade_id', $entidade->empresa->id)
            ->whereYear('data_emissao', $anoAtual)
            ->groupBy(DB::raw('MONTH(data_emissao)'))
            ->orderBy(DB::raw('MONTH(data_emissao)'))
            ->get();

        // Preencher os meses vazios com zero
        $meses = collect(range(1, 12))->map(function ($mes) use ($dados) {
            $info = $dados->firstWhere('mes', $mes);
            return [
                'mes' => $mes,
                'faturamento' => $info->faturamento ?? 0,
                'pedidos' => $info->pedidos ?? 0,
                'lucro' => $info->lucro ?? 0,
            ];
        });

        return response()->json($meses);
    }

    public function produtosMaisVendidos(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $inicio = $request->input('inicio', now()->subDays(14)->format('Y-m-d'));
        $fim    = $request->input('fim', now()->format('Y-m-d'));
        $loja_id    = $request->input('loja_id');

        $produtos = ItemVenda::with(['produto.marca', 'produto.categoria', 'venda'])
            ->whereHas('venda', function ($query) use ($inicio, $fim, $entidade, $loja_id) {
                $query->whereBetween('data_emissao', [$inicio, $fim])
                    ->when($loja_id, function ($q, $v) {
                        $q->where('loja_id', $v);
                    })
                    ->where('entidade_id', $entidade->empresa->id);
            })
            ->selectRaw('produto_id, SUM(quantidade) as total_vendido, SUM(quantidade * preco_unitario) as valor_total, SUM(custo) as lucro_total')
            ->groupBy('produto_id')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        return response()->json($produtos);
    }

    public function pdfProdutosMaisVendidos(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $inicio = $request->input('inicio', now()->subDays(14)->format('Y-m-d'));
        $fim = $request->input('fim', now()->format('Y-m-d'));

        $produtos = ItemVenda::with(['produto.marca', 'produto.categoria', 'venda'])
            ->whereHas('venda', function ($query) use ($inicio, $fim, $entidade) {
                $query->whereBetween('data_emissao', [$inicio, $fim])
                    ->where('entidade_id', $entidade->empresa->id);
            })
            ->selectRaw('produto_id, SUM(quantidade) as total_vendido, SUM(quantidade * preco_unitario) as valor_total, SUM(custo) as lucro_total')
            ->groupBy('produto_id')
            ->orderByDesc('total_vendido')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Relatório de Produtos mais vendidos",
            "descricao" => env("APP_NAME"),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "produtos" => $produtos,
            "dataInicial" => $inicio,
            "dataFinal" => $fim,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.relatorios.pdf_produtos_mais_vendidos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function estoqueCriticoPorLoja(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $lojaId = $request->input('loja_id'); // Loja selecionada no filtro

        // Subquery com agrupamento
        $produtosCriticos = Registro::with(['produto.categoria', 'loja', 'produto.estoques'])
            ->selectRaw('
                produto_id,
                loja_id,
                SUM(CASE WHEN tipo = "E" THEN quantidade ELSE 0 END) as total_entradas,
                SUM(CASE WHEN tipo = "S" THEN quantidade ELSE 0 END) as total_saidas
            ')
            ->where('entidade_id', $entidade->empresa->id)
            ->when($lojaId, fn($q) => $q->where('loja_id', $lojaId))
            ->groupBy('produto_id', 'loja_id')
            ->get()
            ->map(function ($registro) {
                // Obtém o estoque mínimo da loja correspondente
                $estoque = $registro->produto->estoques->firstWhere('loja_id', $registro->loja_id);
                $stockMinimo = $estoque ? $estoque->stock_minimo : 0;
                $saldoAtual = $registro->total_entradas - $registro->total_saidas;
                // Adiciona propriedades ao objeto
                $registro->saldo_atual = $saldoAtual;
                $registro->stock_minimo = $stockMinimo;

                return $registro;
            })
            ->filter(function ($registro) {
                return $registro->saldo_atual < $registro->stock_minimo;
            })
            ->sortBy('saldo_atual')
            ->values();

        return response()->json($produtosCriticos);
    }

    public function exportarEstoqueCriticoPDF(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $lojaId = $request->input('loja_id');

        // Subquery com agrupamento
        $estoquesCriticos = Registro::with(['produto.categoria', 'loja', 'produto.estoques'])
            ->selectRaw('
                produto_id,
                loja_id,
                SUM(CASE WHEN tipo = "E" THEN quantidade ELSE 0 END) as total_entradas,
                SUM(CASE WHEN tipo = "S" THEN quantidade ELSE 0 END) as total_saidas
            ')
            ->where('entidade_id', $entidade->empresa->id)
            ->when($lojaId, fn($q) => $q->where('loja_id', $lojaId))
            ->groupBy('produto_id', 'loja_id')
            ->get()
            ->map(function ($registro) {
                // Obtém o estoque mínimo da loja correspondente
                $estoque = $registro->produto->estoques->firstWhere('loja_id', $registro->loja_id);
                $stockMinimo = $estoque ? $estoque->stock_minimo : 0;
                $saldoAtual = $registro->total_entradas - $registro->total_saidas;
                // Adiciona propriedades ao objeto
                $registro->saldo_atual = $saldoAtual;
                $registro->stock_minimo = $stockMinimo;

                return $registro;
            })
            ->filter(function ($registro) {
                return $registro->saldo_atual < $registro->stock_minimo;
            })
            ->sortBy('saldo_atual')
            ->values();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Relatório de Estoque Crítico",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env("APP_NAME"),
            "estoquesCriticos" => $estoquesCriticos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.relatorios.estoque_critico', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
