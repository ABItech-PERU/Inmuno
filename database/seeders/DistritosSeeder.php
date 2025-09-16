<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Distrito;

class DistritosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distritos = [
            // AMAZONAS - Chachapoyas (0101)
            ['codigo_ubigeo' => '010101', 'nombre' => 'Chachapoyas', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010102', 'nombre' => 'Asunción', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010103', 'nombre' => 'Balsas', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010104', 'nombre' => 'Cheto', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010105', 'nombre' => 'Chiliquín', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010106', 'nombre' => 'Chuquibamba', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010107', 'nombre' => 'Granada', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010108', 'nombre' => 'Huancas', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010109', 'nombre' => 'La Jalca', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010110', 'nombre' => 'Leimebamba', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010111', 'nombre' => 'Levanto', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010112', 'nombre' => 'Magdalena', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010113', 'nombre' => 'Mariscal Castilla', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010114', 'nombre' => 'Molinopampa', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010115', 'nombre' => 'Montevideo', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010116', 'nombre' => 'Olleros', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010117', 'nombre' => 'Quinjalca', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010118', 'nombre' => 'San Francisco de Daguas', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010119', 'nombre' => 'San Isidro de Maino', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010120', 'nombre' => 'Soloco', 'provincia_codigo' => '0101'],
            ['codigo_ubigeo' => '010121', 'nombre' => 'Sonche', 'provincia_codigo' => '0101'],

            // AMAZONAS - Bagua (0102)
            ['codigo_ubigeo' => '010201', 'nombre' => 'La Peca', 'provincia_codigo' => '0102'],
            ['codigo_ubigeo' => '010202', 'nombre' => 'Aramango', 'provincia_codigo' => '0102'],
            ['codigo_ubigeo' => '010203', 'nombre' => 'Copallin', 'provincia_codigo' => '0102'],
            ['codigo_ubigeo' => '010204', 'nombre' => 'El Parco', 'provincia_codigo' => '0102'],
            ['codigo_ubigeo' => '010205', 'nombre' => 'Imaza', 'provincia_codigo' => '0102'],
            ['codigo_ubigeo' => '010206', 'nombre' => 'Bagua', 'provincia_codigo' => '0102'],

            // AMAZONAS - Bongará (0103)
            ['codigo_ubigeo' => '010301', 'nombre' => 'Jumbilla', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010302', 'nombre' => 'Chisquilla', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010303', 'nombre' => 'Churuja', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010304', 'nombre' => 'Corosha', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010305', 'nombre' => 'Cuispes', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010306', 'nombre' => 'Florida', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010307', 'nombre' => 'Jazan', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010308', 'nombre' => 'Recta', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010309', 'nombre' => 'San Carlos', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010310', 'nombre' => 'Shipasbamba', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010311', 'nombre' => 'Valera', 'provincia_codigo' => '0103'],
            ['codigo_ubigeo' => '010312', 'nombre' => 'Yambrasbamba', 'provincia_codigo' => '0103'],

            // AMAZONAS - Condorcanqui (0104)
            ['codigo_ubigeo' => '010401', 'nombre' => 'Santa María de Nieva', 'provincia_codigo' => '0104'],
            ['codigo_ubigeo' => '010402', 'nombre' => 'El Cenepa', 'provincia_codigo' => '0104'],
            ['codigo_ubigeo' => '010403', 'nombre' => 'Río Santiago', 'provincia_codigo' => '0104'],

            // AMAZONAS - Luya (0105)
            ['codigo_ubigeo' => '010501', 'nombre' => 'Lamud', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010502', 'nombre' => 'Camporredondo', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010503', 'nombre' => 'Cocabamba', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010504', 'nombre' => 'Colcamar', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010505', 'nombre' => 'Conila', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010506', 'nombre' => 'Inguilpata', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010507', 'nombre' => 'Longuita', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010508', 'nombre' => 'Lonya Chico', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010509', 'nombre' => 'Luya', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010510', 'nombre' => 'Luya Viejo', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010511', 'nombre' => 'María', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010512', 'nombre' => 'Ocalli', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010513', 'nombre' => 'Ocumal', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010514', 'nombre' => 'Pisuquia', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010515', 'nombre' => 'Providencia', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010516', 'nombre' => 'San Cristóbal', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010517', 'nombre' => 'San Francisco del Yeso', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010518', 'nombre' => 'San Jerónimo', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010519', 'nombre' => 'San Juan de Lopecancha', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010520', 'nombre' => 'Santa Catalina', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010521', 'nombre' => 'Santo Tomas', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010522', 'nombre' => 'Tingo', 'provincia_codigo' => '0105'],
            ['codigo_ubigeo' => '010523', 'nombre' => 'Trita', 'provincia_codigo' => '0105'],

            // AMAZONAS - Rodríguez de Mendoza (0106)
            ['codigo_ubigeo' => '010601', 'nombre' => 'San Nicolás', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010602', 'nombre' => 'Chirimoto', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010603', 'nombre' => 'Cochamal', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010604', 'nombre' => 'Huambo', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010605', 'nombre' => 'Limabamba', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010606', 'nombre' => 'Longar', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010607', 'nombre' => 'Mariscal Benavides', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010608', 'nombre' => 'Milpuc', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010609', 'nombre' => 'Omia', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010610', 'nombre' => 'Santa Rosa', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010611', 'nombre' => 'Totora', 'provincia_codigo' => '0106'],
            ['codigo_ubigeo' => '010612', 'nombre' => 'Vista Alegre', 'provincia_codigo' => '0106'],

            // AMAZONAS - Utcubamba (0107)
            ['codigo_ubigeo' => '010701', 'nombre' => 'Bagua Grande', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010702', 'nombre' => 'Cajaruro', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010703', 'nombre' => 'Cumba', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010704', 'nombre' => 'El Milagro', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010705', 'nombre' => 'Jamalca', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010706', 'nombre' => 'Lonya Grande', 'provincia_codigo' => '0107'],
            ['codigo_ubigeo' => '010707', 'nombre' => 'Yamon', 'provincia_codigo' => '0107'],


            // ÁNCASH - Huaraz (0201)
            ['codigo_ubigeo' => '020101', 'nombre' => 'Huaraz', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020102', 'nombre' => 'Cochabamba', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020103', 'nombre' => 'Colcabamba', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020104', 'nombre' => 'Huanchay', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020105', 'nombre' => 'Independencia', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020106', 'nombre' => 'Jangas', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020107', 'nombre' => 'La Libertad', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020108', 'nombre' => 'Olleros', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020109', 'nombre' => 'Pampas Grande', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020110', 'nombre' => 'Pariacoto', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020111', 'nombre' => 'Pira', 'provincia_codigo' => '0201'],
            ['codigo_ubigeo' => '020112', 'nombre' => 'Tarica', 'provincia_codigo' => '0201'],

            // ÁNCASH - Aija (0202)
            ['codigo_ubigeo' => '020201', 'nombre' => 'Aija', 'provincia_codigo' => '0202'],
            ['codigo_ubigeo' => '020202', 'nombre' => 'Coris', 'provincia_codigo' => '0202'],
            ['codigo_ubigeo' => '020203', 'nombre' => 'Huacllán', 'provincia_codigo' => '0202'],
            ['codigo_ubigeo' => '020204', 'nombre' => 'La Merced', 'provincia_codigo' => '0202'],
            ['codigo_ubigeo' => '020205', 'nombre' => 'Succha', 'provincia_codigo' => '0202'],

            // ÁNCASH - Antonio Raymondi (0203)
            ['codigo_ubigeo' => '020301', 'nombre' => 'Llamellín', 'provincia_codigo' => '0203'],
            ['codigo_ubigeo' => '020302', 'nombre' => 'Aczo', 'provincia_codigo' => '0203'],
            ['codigo_ubigeo' => '020303', 'nombre' => 'Chaccho', 'provincia_codigo' => '0203'],
            ['codigo_ubigeo' => '020304', 'nombre' => 'Chingas', 'provincia_codigo' => '0203'],
            ['codigo_ubigeo' => '020305', 'nombre' => 'Mirgas', 'provincia_codigo' => '0203'],
            ['codigo_ubigeo' => '020306', 'nombre' => 'San Juan de Rontoy', 'provincia_codigo' => '0203'],

            // ÁNCASH - Asunción (0204)
            ['codigo_ubigeo' => '020401', 'nombre' => 'Chacas', 'provincia_codigo' => '0204'],
            ['codigo_ubigeo' => '020402', 'nombre' => 'Acochaca', 'provincia_codigo' => '0204'],

            // ÁNCASH - Bolognesi (0205)
            ['codigo_ubigeo' => '020501', 'nombre' => 'Chiquián', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020502', 'nombre' => 'Abelardo Pardo Lezameta', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020503', 'nombre' => 'Antonio Raymondi', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020504', 'nombre' => 'Aquia', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020505', 'nombre' => 'Cajacay', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020506', 'nombre' => 'Canis', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020507', 'nombre' => 'Colquioc', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020508', 'nombre' => 'Huallanca', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020509', 'nombre' => 'Huasta', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020510', 'nombre' => 'Huayllacayán', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020511', 'nombre' => 'La Primavera', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020512', 'nombre' => 'Mangas', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020513', 'nombre' => 'Pacllon', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020514', 'nombre' => 'San Miguel de Corpanqui', 'provincia_codigo' => '0205'],
            ['codigo_ubigeo' => '020515', 'nombre' => 'Ticllos', 'provincia_codigo' => '0205'],

            // ÁNCASH - Carhuaz (0206)
            ['codigo_ubigeo' => '020601', 'nombre' => 'Carhuaz', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020602', 'nombre' => 'Acopampa', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020603', 'nombre' => 'Amashca', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020604', 'nombre' => 'Anta', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020605', 'nombre' => 'Ataquero', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020606', 'nombre' => 'Marcará', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020607', 'nombre' => 'Pariahuanca', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020608', 'nombre' => 'San Miguel de Aco', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020609', 'nombre' => 'Shilla', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020610', 'nombre' => 'Tinco', 'provincia_codigo' => '0206'],
            ['codigo_ubigeo' => '020611', 'nombre' => 'Yungar', 'provincia_codigo' => '0206'],

            // ÁNCASH - Carlos Fermín Fitzcarrald (0207)
            ['codigo_ubigeo' => '020701', 'nombre' => 'San Luis', 'provincia_codigo' => '0207'],
            ['codigo_ubigeo' => '020702', 'nombre' => 'San Nicolás', 'provincia_codigo' => '0207'],
            ['codigo_ubigeo' => '020703', 'nombre' => 'Yauya', 'provincia_codigo' => '0207'],

            // ÁNCASH - Casma (0208)
            ['codigo_ubigeo' => '020801', 'nombre' => 'Casma', 'provincia_codigo' => '0208'],
            ['codigo_ubigeo' => '020802', 'nombre' => 'Buena Vista Alta', 'provincia_codigo' => '0208'],
            ['codigo_ubigeo' => '020803', 'nombre' => 'Comandante Noel', 'provincia_codigo' => '0208'],
            ['codigo_ubigeo' => '020804', 'nombre' => 'Yaitan', 'provincia_codigo' => '0208'],

            // ÁNCASH - Corongo (0209)
            ['codigo_ubigeo' => '020901', 'nombre' => 'Corongo', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020902', 'nombre' => 'Aco', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020903', 'nombre' => 'Bambas', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020904', 'nombre' => 'Cusca', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020905', 'nombre' => 'La Pampa', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020906', 'nombre' => 'Yanac', 'provincia_codigo' => '0209'],
            ['codigo_ubigeo' => '020907', 'nombre' => 'Yupan', 'provincia_codigo' => '0209'],

            // ÁNCASH - Huari (0210)
            ['codigo_ubigeo' => '021001', 'nombre' => 'Huari', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021002', 'nombre' => 'Anra', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021003', 'nombre' => 'Cajay', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021004', 'nombre' => 'Chavin de Huantar', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021005', 'nombre' => 'Huacachi', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021006', 'nombre' => 'Huacchis', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021007', 'nombre' => 'Huachis', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021008', 'nombre' => 'Huantar', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021009', 'nombre' => 'Masin', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021010', 'nombre' => 'Paucas', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021011', 'nombre' => 'Ponto', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021012', 'nombre' => 'Rahuapampa', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021013', 'nombre' => 'Rapayan', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021014', 'nombre' => 'San Marcos', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021015', 'nombre' => 'San Pedro de Chana', 'provincia_codigo' => '0210'],
            ['codigo_ubigeo' => '021016', 'nombre' => 'Uco', 'provincia_codigo' => '0210'],

            // ÁNCASH - Huarmey (0211)
            ['codigo_ubigeo' => '021101', 'nombre' => 'Huarmey', 'provincia_codigo' => '0211'],
            ['codigo_ubigeo' => '021102', 'nombre' => 'Cochapeti', 'provincia_codigo' => '0211'],
            ['codigo_ubigeo' => '021103', 'nombre' => 'Culebras', 'provincia_codigo' => '0211'],
            ['codigo_ubigeo' => '021104', 'nombre' => 'Huayan', 'provincia_codigo' => '0211'],
            ['codigo_ubigeo' => '021105', 'nombre' => 'Malvas', 'provincia_codigo' => '0211'],

            // ÁNCASH - Huaylas (0212)
            ['codigo_ubigeo' => '021201', 'nombre' => 'Caraz', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021202', 'nombre' => 'Huallanca', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021203', 'nombre' => 'Huata', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021204', 'nombre' => 'Huaylas', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021205', 'nombre' => 'Mato', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021206', 'nombre' => 'Pamparomas', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021207', 'nombre' => 'Pueblo Libre', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021208', 'nombre' => 'Santa Cruz', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021209', 'nombre' => 'Santo Toribio', 'provincia_codigo' => '0212'],
            ['codigo_ubigeo' => '021210', 'nombre' => 'Yuracmarca', 'provincia_codigo' => '0212'],

            // ÁNCASH - Mariscal Luzuriaga (0213)
            ['codigo_ubigeo' => '021301', 'nombre' => 'Piscobamba', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021302', 'nombre' => 'Casca', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021303', 'nombre' => 'Eleazar Guzmán Barron', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021304', 'nombre' => 'Fidel Olivas Escudero', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021305', 'nombre' => 'Llama', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021306', 'nombre' => 'Llumpa', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021307', 'nombre' => 'Lucma', 'provincia_codigo' => '0213'],
            ['codigo_ubigeo' => '021308', 'nombre' => 'Musga', 'provincia_codigo' => '0213'],

            // ÁNCASH - Ocros (0214)
            ['codigo_ubigeo' => '021401', 'nombre' => 'Ocros', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021402', 'nombre' => 'Acas', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021403', 'nombre' => 'Cajamarquilla', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021404', 'nombre' => 'Carhuapampa', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021405', 'nombre' => 'Cochas', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021406', 'nombre' => 'Congas', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021407', 'nombre' => 'Llipa', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021408', 'nombre' => 'San Cristobal de Rajan', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021409', 'nombre' => 'San Pedro', 'provincia_codigo' => '0214'],
            ['codigo_ubigeo' => '021410', 'nombre' => 'Santiago de Chilcas', 'provincia_codigo' => '0214'],

            // ÁNCASH - Pallasca (0215)
            ['codigo_ubigeo' => '021501', 'nombre' => 'Cabana', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021502', 'nombre' => 'Bolognesi', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021503', 'nombre' => 'Conchucos', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021504', 'nombre' => 'Huacaschuque', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021505', 'nombre' => 'Huandoval', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021506', 'nombre' => 'Lacabamba', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021507', 'nombre' => 'Llapo', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021508', 'nombre' => 'Pallasca', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021509', 'nombre' => 'Pampas', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021510', 'nombre' => 'Santa Rosa', 'provincia_codigo' => '0215'],
            ['codigo_ubigeo' => '021511', 'nombre' => 'Tauca', 'provincia_codigo' => '0215'],

            // ÁNCASH - Pomabamba (0216)
            ['codigo_ubigeo' => '021601', 'nombre' => 'Pomabamba', 'provincia_codigo' => '0216'],
            ['codigo_ubigeo' => '021602', 'nombre' => 'Huayllan', 'provincia_codigo' => '0216'],
            ['codigo_ubigeo' => '021603', 'nombre' => 'Parobamba', 'provincia_codigo' => '0216'],
            ['codigo_ubigeo' => '021604', 'nombre' => 'Quinuabamba', 'provincia_codigo' => '0216'],

            // ÁNCASH - Recuay (0217)
            ['codigo_ubigeo' => '021701', 'nombre' => 'Recuay', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021702', 'nombre' => 'Catac', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021703', 'nombre' => 'Cotaparaco', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021704', 'nombre' => 'Huayllapampa', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021705', 'nombre' => 'Llacllin', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021706', 'nombre' => 'Marca', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021707', 'nombre' => 'Pampas Chico', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021708', 'nombre' => 'Pararin', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021709', 'nombre' => 'Tapacocha', 'provincia_codigo' => '0217'],
            ['codigo_ubigeo' => '021710', 'nombre' => 'Ticapampa', 'provincia_codigo' => '0217'],

            // ÁNCASH - Santa (0218)
            ['codigo_ubigeo' => '021801', 'nombre' => 'Chimbote', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021802', 'nombre' => 'Cáceres del Perú', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021803', 'nombre' => 'Coishco', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021804', 'nombre' => 'Macate', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021805', 'nombre' => 'Moro', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021806', 'nombre' => 'Nepeña', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021807', 'nombre' => 'Samanco', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021808', 'nombre' => 'Santa', 'provincia_codigo' => '0218'],
            ['codigo_ubigeo' => '021809', 'nombre' => 'Nuevo Chimbote', 'provincia_codigo' => '0218'],

            // ÁNCASH - Sihuas (0219)
            ['codigo_ubigeo' => '021901', 'nombre' => 'Sihuas', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021902', 'nombre' => 'Acobamba', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021903', 'nombre' => 'Alfonso Ugarte', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021904', 'nombre' => 'Cashapampa', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021905', 'nombre' => 'Chingalpo', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021906', 'nombre' => 'Huayllabamba', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021907', 'nombre' => 'Quiches', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021908', 'nombre' => 'Ragash', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021909', 'nombre' => 'San Juan', 'provincia_codigo' => '0219'],
            ['codigo_ubigeo' => '021910', 'nombre' => 'Sicsibamba', 'provincia_codigo' => '0219'],

            // ÁNCASH - Yungay (0220)
            ['codigo_ubigeo' => '022001', 'nombre' => 'Yungay', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022002', 'nombre' => 'Cascapara', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022003', 'nombre' => 'Mancos', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022004', 'nombre' => 'Matacoto', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022005', 'nombre' => 'Quillo', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022006', 'nombre' => 'Ranrahirca', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022007', 'nombre' => 'Shupluy', 'provincia_codigo' => '0220'],
            ['codigo_ubigeo' => '022008', 'nombre' => 'Yanama', 'provincia_codigo' => '0220'],


            // APURÍMAC - Abancay (0301)
            ['codigo_ubigeo' => '030101', 'nombre' => 'Abancay', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030102', 'nombre' => 'Chacoche', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030103', 'nombre' => 'Circa', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030104', 'nombre' => 'Curahuasi', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030105', 'nombre' => 'Huanipaca', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030106', 'nombre' => 'Lambrama', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030107', 'nombre' => 'Pichirhua', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030108', 'nombre' => 'San Pedro de Cachora', 'provincia_codigo' => '0301'],
            ['codigo_ubigeo' => '030109', 'nombre' => 'Tamburco', 'provincia_codigo' => '0301'],

            // APURÍMAC - Andahuaylas (0302)
            ['codigo_ubigeo' => '030201', 'nombre' => 'Andahuaylas', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030202', 'nombre' => 'Andarapa', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030203', 'nombre' => 'Chiara', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030204', 'nombre' => 'Huancarama', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030205', 'nombre' => 'Huancaray', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030206', 'nombre' => 'Huayana', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030207', 'nombre' => 'Kishuara', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030208', 'nombre' => 'Pacobamba', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030209', 'nombre' => 'Pacucha', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030210', 'nombre' => 'Pampachiri', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030211', 'nombre' => 'Pomacocha', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030212', 'nombre' => 'San Antonio de Cachi', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030213', 'nombre' => 'San Jerónimo', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030214', 'nombre' => 'San Miguel de Chaccrampa', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030215', 'nombre' => 'Santa María de Chicmo', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030216', 'nombre' => 'Talavera', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030217', 'nombre' => 'Tumay Huaraca', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030218', 'nombre' => 'Turpo', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030219', 'nombre' => 'Kaquiabamba', 'provincia_codigo' => '0302'],
            ['codigo_ubigeo' => '030220', 'nombre' => 'José María Arguedas', 'provincia_codigo' => '0302'],

            // APURÍMAC - Antabamba (0303)
            ['codigo_ubigeo' => '030301', 'nombre' => 'Antabamba', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030302', 'nombre' => 'El Oro', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030303', 'nombre' => 'Huaquirca', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030304', 'nombre' => 'Juan Espinoza Medrano', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030305', 'nombre' => 'Oropesa', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030306', 'nombre' => 'Pachaconas', 'provincia_codigo' => '0303'],
            ['codigo_ubigeo' => '030307', 'nombre' => 'Sabaino', 'provincia_codigo' => '0303'],

            // APURÍMAC - Aymaraes (0304)
            ['codigo_ubigeo' => '030401', 'nombre' => 'Chalhuanca', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030402', 'nombre' => 'Capaya', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030403', 'nombre' => 'Caraybamba', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030404', 'nombre' => 'Chapimarca', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030405', 'nombre' => 'Colcabamba', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030406', 'nombre' => 'Cotaruse', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030407', 'nombre' => 'Ihuayllo', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030408', 'nombre' => 'Justo Apu Sahuaraura', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030409', 'nombre' => 'Lucre', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030410', 'nombre' => 'Pocohuanca', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030411', 'nombre' => 'San Juan de Chacña', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030412', 'nombre' => 'Sañayca', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030413', 'nombre' => 'Soraya', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030414', 'nombre' => 'Tapairihua', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030415', 'nombre' => 'Tintay', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030416', 'nombre' => 'Toraya', 'provincia_codigo' => '0304'],
            ['codigo_ubigeo' => '030417', 'nombre' => 'Yanaca', 'provincia_codigo' => '0304'],

            // APURÍMAC - Cotabambas (0305)
            ['codigo_ubigeo' => '030501', 'nombre' => 'Tambobamba', 'provincia_codigo' => '0305'],
            ['codigo_ubigeo' => '030502', 'nombre' => 'Cotabambas', 'provincia_codigo' => '0305'],
            ['codigo_ubigeo' => '030503', 'nombre' => 'Coyllurqui', 'provincia_codigo' => '0305'],
            ['codigo_ubigeo' => '030504', 'nombre' => 'Haquira', 'provincia_codigo' => '0305'],
            ['codigo_ubigeo' => '030505', 'nombre' => 'Mara', 'provincia_codigo' => '0305'],
            ['codigo_ubigeo' => '030506', 'nombre' => 'Challhuahuacho', 'provincia_codigo' => '0305'],

            // APURÍMAC - Chincheros (0306)
            ['codigo_ubigeo' => '030601', 'nombre' => 'Chincheros', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030602', 'nombre' => 'Anco-Huallo', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030603', 'nombre' => 'Cocharcas', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030604', 'nombre' => 'Huaccana', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030605', 'nombre' => 'Ocobamba', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030606', 'nombre' => 'Ongoy', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030607', 'nombre' => 'Uranmarca', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030608', 'nombre' => 'Ranracancha', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030609', 'nombre' => 'Rocchacc', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030610', 'nombre' => 'El Porvenir', 'provincia_codigo' => '0306'],
            ['codigo_ubigeo' => '030611', 'nombre' => 'Los Chankas', 'provincia_codigo' => '0306'],

            // APURÍMAC - Grau (0307)
            ['codigo_ubigeo' => '030701', 'nombre' => 'Chuquibambilla', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030702', 'nombre' => 'Curpahuasi', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030703', 'nombre' => 'Gamarra', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030704', 'nombre' => 'Huayllati', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030705', 'nombre' => 'Mamara', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030706', 'nombre' => 'Micaela Bastidas', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030707', 'nombre' => 'Pataypampa', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030708', 'nombre' => 'Progreso', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030709', 'nombre' => 'San Antonio', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030710', 'nombre' => 'Santa Rosa', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030711', 'nombre' => 'Turpay', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030712', 'nombre' => 'Vilcabamba', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030713', 'nombre' => 'Virundo', 'provincia_codigo' => '0307'],
            ['codigo_ubigeo' => '030714', 'nombre' => 'Curasco', 'provincia_codigo' => '0307'],


            // AREQUIPA - Arequipa (0401)
            ['codigo_ubigeo' => '040101', 'nombre' => 'Arequipa', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040102', 'nombre' => 'Alto Selva Alegre', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040103', 'nombre' => 'Cayma', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040104', 'nombre' => 'Cerro Colorado', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040105', 'nombre' => 'Characato', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040106', 'nombre' => 'Chiguata', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040107', 'nombre' => 'Jacoby Hunter', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040108', 'nombre' => 'La Joya', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040109', 'nombre' => 'Mariano Melgar', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040110', 'nombre' => 'Miraflores', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040111', 'nombre' => 'Mollebaya', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040112', 'nombre' => 'Paucarpata', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040113', 'nombre' => 'Pocsi', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040114', 'nombre' => 'Polobaya', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040115', 'nombre' => 'Quequeña', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040116', 'nombre' => 'Sabandia', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040117', 'nombre' => 'Sachaca', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040118', 'nombre' => 'San Juan de Siguas', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040119', 'nombre' => 'San Juan de Tarucani', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040120', 'nombre' => 'Santa Isabel de Siguas', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040121', 'nombre' => 'Santa Rita de Siguas', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040122', 'nombre' => 'Socabaya', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040123', 'nombre' => 'Tiabaya', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040124', 'nombre' => 'Uchumayo', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040125', 'nombre' => 'Vitor', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040126', 'nombre' => 'Yanahuara', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040127', 'nombre' => 'Yarabamba', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040128', 'nombre' => 'Yura', 'provincia_codigo' => '0401'],
            ['codigo_ubigeo' => '040129', 'nombre' => 'José Luis Bustamante y Rivero', 'provincia_codigo' => '0401'],

            // AREQUIPA - Camaná (0402)
            ['codigo_ubigeo' => '040201', 'nombre' => 'Camaná', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040202', 'nombre' => 'José María Quimper', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040203', 'nombre' => 'Mariano Nicolás Valcárcel', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040204', 'nombre' => 'Mariscal Cáceres', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040205', 'nombre' => 'Nicolás de Piérola', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040206', 'nombre' => 'Ocoña', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040207', 'nombre' => 'Quilca', 'provincia_codigo' => '0402'],
            ['codigo_ubigeo' => '040208', 'nombre' => 'Samuel Pastor', 'provincia_codigo' => '0402'],

            // AREQUIPA - Caravelí (0403)
            ['codigo_ubigeo' => '040301', 'nombre' => 'Caravelí', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040302', 'nombre' => 'Acarí', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040303', 'nombre' => 'Atico', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040304', 'nombre' => 'Atiquipa', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040305', 'nombre' => 'Bella Unión', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040306', 'nombre' => 'Cahuacho', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040307', 'nombre' => 'Chala', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040308', 'nombre' => 'Chaparra', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040309', 'nombre' => 'Huanuhuanu', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040310', 'nombre' => 'Jaqui', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040311', 'nombre' => 'Lomas', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040312', 'nombre' => 'Quicacha', 'provincia_codigo' => '0403'],
            ['codigo_ubigeo' => '040313', 'nombre' => 'Yauca', 'provincia_codigo' => '0403'],

            // AREQUIPA - Castilla (0404)
            ['codigo_ubigeo' => '040401', 'nombre' => 'Aplao', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040402', 'nombre' => 'Andagua', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040403', 'nombre' => 'Ayo', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040404', 'nombre' => 'Chachas', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040405', 'nombre' => 'Chilcaymarca', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040406', 'nombre' => 'Choco', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040407', 'nombre' => 'Huancarqui', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040408', 'nombre' => 'Machaguay', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040409', 'nombre' => 'Orcopampa', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040410', 'nombre' => 'Pampacolca', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040411', 'nombre' => 'Tipan', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040412', 'nombre' => 'Uñon', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040413', 'nombre' => 'Uraca', 'provincia_codigo' => '0404'],
            ['codigo_ubigeo' => '040414', 'nombre' => 'Viraco', 'provincia_codigo' => '0404'],

            // AREQUIPA - Caylloma (0405)
            ['codigo_ubigeo' => '040501', 'nombre' => 'Chivay', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040502', 'nombre' => 'Achoma', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040503', 'nombre' => 'Cabanaconde', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040504', 'nombre' => 'Callalli', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040505', 'nombre' => 'Caylloma', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040506', 'nombre' => 'Coporaque', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040507', 'nombre' => 'Huambo', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040508', 'nombre' => 'Huanca', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040509', 'nombre' => 'Ichupampa', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040510', 'nombre' => 'Lari', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040511', 'nombre' => 'Lluta', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040512', 'nombre' => 'Maca', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040513', 'nombre' => 'Madrigal', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040514', 'nombre' => 'San Antonio de Chuca', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040515', 'nombre' => 'Sibayo', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040516', 'nombre' => 'Tapay', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040517', 'nombre' => 'Tisco', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040518', 'nombre' => 'Tuti', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040519', 'nombre' => 'Yanque', 'provincia_codigo' => '0405'],
            ['codigo_ubigeo' => '040520', 'nombre' => 'Majes', 'provincia_codigo' => '0405'],

            // AREQUIPA - Condesuyos (0406)
            ['codigo_ubigeo' => '040601', 'nombre' => 'Chuquibamba', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040602', 'nombre' => 'Andaray', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040603', 'nombre' => 'Cayarani', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040604', 'nombre' => 'Chichas', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040605', 'nombre' => 'Iray', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040606', 'nombre' => 'Río Grande', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040607', 'nombre' => 'Salamanca', 'provincia_codigo' => '0406'],
            ['codigo_ubigeo' => '040608', 'nombre' => 'Yanaquihua', 'provincia_codigo' => '0406'],

            // AREQUIPA - Islay (0407)
            ['codigo_ubigeo' => '040701', 'nombre' => 'Mollendo', 'provincia_codigo' => '0407'],
            ['codigo_ubigeo' => '040702', 'nombre' => 'Cocachacra', 'provincia_codigo' => '0407'],
            ['codigo_ubigeo' => '040703', 'nombre' => 'Dean Valdivia', 'provincia_codigo' => '0407'],
            ['codigo_ubigeo' => '040704', 'nombre' => 'Islay', 'provincia_codigo' => '0407'],
            ['codigo_ubigeo' => '040705', 'nombre' => 'Mejía', 'provincia_codigo' => '0407'],
            ['codigo_ubigeo' => '040706', 'nombre' => 'Punta de Bombón', 'provincia_codigo' => '0407'],

            // AREQUIPA - La Unión (0408)
            ['codigo_ubigeo' => '040801', 'nombre' => 'Cotahuasi', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040802', 'nombre' => 'Alca', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040803', 'nombre' => 'Charcana', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040804', 'nombre' => 'Huaynacotas', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040805', 'nombre' => 'Pampamarca', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040806', 'nombre' => 'Puyca', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040807', 'nombre' => 'Quechualla', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040808', 'nombre' => 'Sayla', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040809', 'nombre' => 'Tauria', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040810', 'nombre' => 'Tomepampa', 'provincia_codigo' => '0408'],
            ['codigo_ubigeo' => '040811', 'nombre' => 'Toro', 'provincia_codigo' => '0408'],


            // HUÁNUCO - Huánuco (1001)
            ['codigo_ubigeo' => '100101', 'nombre' => 'Huánuco', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100102', 'nombre' => 'Amarilis', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100103', 'nombre' => 'Chinchao', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100104', 'nombre' => 'Churubamba', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100105', 'nombre' => 'Margos', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100106', 'nombre' => 'Quisqui (Kichki)', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100107', 'nombre' => 'San Francisco de Cayrán', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100108', 'nombre' => 'San Pedro de Chaulán', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100109', 'nombre' => 'Santa María del Valle', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100110', 'nombre' => 'Yarumayo', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100111', 'nombre' => 'Pillco Marca', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100112', 'nombre' => 'Yacus', 'provincia_codigo' => '1001'],
            ['codigo_ubigeo' => '100113', 'nombre' => 'San Pablo de Pillao', 'provincia_codigo' => '1001'],

            // HUÁNUCO - Ambo (1002)
            ['codigo_ubigeo' => '100201', 'nombre' => 'Ambo', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100202', 'nombre' => 'Cayna', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100203', 'nombre' => 'Colpas', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100204', 'nombre' => 'Conchamarca', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100205', 'nombre' => 'Huácar', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100206', 'nombre' => 'San Francisco', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100207', 'nombre' => 'San Rafael', 'provincia_codigo' => '1002'],
            ['codigo_ubigeo' => '100208', 'nombre' => 'Tomay Kichwa', 'provincia_codigo' => '1002'],

            // HUÁNUCO - Dos de Mayo (1003)
            ['codigo_ubigeo' => '100301', 'nombre' => 'La Unión', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100307', 'nombre' => 'Chuquis', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100311', 'nombre' => 'Marías', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100313', 'nombre' => 'Pachas', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100316', 'nombre' => 'Quivilla', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100317', 'nombre' => 'Ripan', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100321', 'nombre' => 'Shunqui', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100322', 'nombre' => 'Sillapata', 'provincia_codigo' => '1003'],
            ['codigo_ubigeo' => '100323', 'nombre' => 'Yanas', 'provincia_codigo' => '1003'],

            // HUÁNUCO - Huacaybamba (1004)
            ['codigo_ubigeo' => '100401', 'nombre' => 'Huacaybamba', 'provincia_codigo' => '1004'],
            ['codigo_ubigeo' => '100402', 'nombre' => 'Canchabamba', 'provincia_codigo' => '1004'],
            ['codigo_ubigeo' => '100403', 'nombre' => 'Cochabamba', 'provincia_codigo' => '1004'],
            ['codigo_ubigeo' => '100404', 'nombre' => 'Pinra', 'provincia_codigo' => '1004'],

            // HUÁNUCO - Huamalíes (1005)
            ['codigo_ubigeo' => '100501', 'nombre' => 'Llata', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100502', 'nombre' => 'Arancay', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100503', 'nombre' => 'Chavín de Pariarca', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100504', 'nombre' => 'Jacas Grande', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100505', 'nombre' => 'Jircan', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100506', 'nombre' => 'Miraflores', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100507', 'nombre' => 'Monzón', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100508', 'nombre' => 'Punchao', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100509', 'nombre' => 'Puños', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100510', 'nombre' => 'Singa', 'provincia_codigo' => '1005'],
            ['codigo_ubigeo' => '100511', 'nombre' => 'Tantamayo', 'provincia_codigo' => '1005'],

            // HUÁNUCO - Leoncio Prado (1006)
            ['codigo_ubigeo' => '100601', 'nombre' => 'Rupa-Rupa', 'provincia_codigo' => '1006'],
            ['codigo_ubigeo' => '100602', 'nombre' => 'Daniel Alomía Robles', 'provincia_codigo' => '1006'],
            ['codigo_ubigeo' => '100603', 'nombre' => 'Hermílio Valdizán', 'provincia_codigo' => '1006'],
            ['codigo_ubigeo' => '100604', 'nombre' => 'José Crespo y Castillo', 'provincia_codigo' => '1006'],
            ['codigo_ubigeo' => '100605', 'nombre' => 'Luyando', 'provincia_codigo' => '1006'],
            ['codigo_ubigeo' => '100606', 'nombre' => 'Mariano Damaso Beraun', 'provincia_codigo' => '1006'],

            // HUÁNUCO - Marañón (1007)
            ['codigo_ubigeo' => '100701', 'nombre' => 'Huacrachuco', 'provincia_codigo' => '1007'],
            ['codigo_ubigeo' => '100702', 'nombre' => 'Cholon', 'provincia_codigo' => '1007'],
            ['codigo_ubigeo' => '100703', 'nombre' => 'San Buenaventura', 'provincia_codigo' => '1007'],

            // HUÁNUCO - Pachitea (1008)
            ['codigo_ubigeo' => '100801', 'nombre' => 'Panao', 'provincia_codigo' => '1008'],
            ['codigo_ubigeo' => '100802', 'nombre' => 'Chaglla', 'provincia_codigo' => '1008'],
            ['codigo_ubigeo' => '100803', 'nombre' => 'Molino', 'provincia_codigo' => '1008'],
            ['codigo_ubigeo' => '100804', 'nombre' => 'Umari', 'provincia_codigo' => '1008'],

            // HUÁNUCO - Puerto Inca (1009)
            ['codigo_ubigeo' => '100901', 'nombre' => 'Puerto Inca', 'provincia_codigo' => '1009'],
            ['codigo_ubigeo' => '100902', 'nombre' => 'Codo del Pozuzo', 'provincia_codigo' => '1009'],
            ['codigo_ubigeo' => '100903', 'nombre' => 'Honoria', 'provincia_codigo' => '1009'],
            ['codigo_ubigeo' => '100904', 'nombre' => 'Tournavista', 'provincia_codigo' => '1009'],
            ['codigo_ubigeo' => '100905', 'nombre' => 'Yuyapichis', 'provincia_codigo' => '1009'],

            // HUÁNUCO - Lauricocha (1010)
            ['codigo_ubigeo' => '101001', 'nombre' => 'Jesús', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101002', 'nombre' => 'Baños', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101003', 'nombre' => 'Jivia', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101004', 'nombre' => 'Queropalca', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101005', 'nombre' => 'Rondos', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101006', 'nombre' => 'San Francisco de Asís', 'provincia_codigo' => '1010'],
            ['codigo_ubigeo' => '101007', 'nombre' => 'San Miguel de Cauri', 'provincia_codigo' => '1010'],

            // HUÁNUCO - Yarowilca (1011)
            ['codigo_ubigeo' => '101101', 'nombre' => 'Chavinillo', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101102', 'nombre' => 'Cahuac', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101103', 'nombre' => 'Chacabamba', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101104', 'nombre' => 'Aparicio Pomares', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101105', 'nombre' => 'Jacas Chico', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101106', 'nombre' => 'Obas', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101107', 'nombre' => 'Pampamarca', 'provincia_codigo' => '1011'],
            ['codigo_ubigeo' => '101108', 'nombre' => 'Choras', 'provincia_codigo' => '1011'],

        ];
        foreach ($distritos as $distrito) {
            Distrito::create($distrito);
        }
    }
}
