<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provincia;

class ProvinciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            // Amazonas (01)
            ['codigo_ubigeo' => '0101', 'nombre' => 'Chachapoyas', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0102', 'nombre' => 'Bagua', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0103', 'nombre' => 'Bongará', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0104', 'nombre' => 'Condorcanqui', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0105', 'nombre' => 'Luya', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0106', 'nombre' => 'Rodríguez de Mendoza', 'departamento_codigo' => '01'],
            ['codigo_ubigeo' => '0107', 'nombre' => 'Utcubamba', 'departamento_codigo' => '01'],

            // Áncash (02)
            ['codigo_ubigeo' => '0201', 'nombre' => 'Huaraz', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0202', 'nombre' => 'Aija', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0203', 'nombre' => 'Antonio Raymondi', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0204', 'nombre' => 'Asunción', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0205', 'nombre' => 'Bolognesi', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0206', 'nombre' => 'Carhuaz', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0207', 'nombre' => 'Carlos Fermín Fitzcarrald', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0208', 'nombre' => 'Casma', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0209', 'nombre' => 'Corongo', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0210', 'nombre' => 'Huari', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0211', 'nombre' => 'Huarmey', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0212', 'nombre' => 'Huaylas', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0213', 'nombre' => 'Mariscal Luzuriaga', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0214', 'nombre' => 'Ocros', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0215', 'nombre' => 'Pallasca', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0216', 'nombre' => 'Pomabamba', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0217', 'nombre' => 'Recuay', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0218', 'nombre' => 'Santa', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0219', 'nombre' => 'Sihuas', 'departamento_codigo' => '02'],
            ['codigo_ubigeo' => '0220', 'nombre' => 'Yungay', 'departamento_codigo' => '02'],

            // Apurímac (03)
            ['codigo_ubigeo' => '0301', 'nombre' => 'Abancay', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0302', 'nombre' => 'Andahuaylas', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0303', 'nombre' => 'Antabamba', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0304', 'nombre' => 'Aymaraes', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0305', 'nombre' => 'Cotabambas', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0306', 'nombre' => 'Chincheros', 'departamento_codigo' => '03'],
            ['codigo_ubigeo' => '0307', 'nombre' => 'Grau', 'departamento_codigo' => '03'],

            // Arequipa (04)
            ['codigo_ubigeo' => '0401', 'nombre' => 'Arequipa', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0402', 'nombre' => 'Camana', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0403', 'nombre' => 'Caraveli', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0404', 'nombre' => 'Castilla', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0405', 'nombre' => 'Caylloma', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0406', 'nombre' => 'Condesuyos', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0407', 'nombre' => 'Islay', 'departamento_codigo' => '04'],
            ['codigo_ubigeo' => '0408', 'nombre' => 'La Unión', 'departamento_codigo' => '04'],

            // Ayacucho (05)
            ['codigo_ubigeo' => '0501', 'nombre' => 'Huamanga', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0502', 'nombre' => 'Cangallo', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0503', 'nombre' => 'Huanca Sancos', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0504', 'nombre' => 'Huanta', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0505', 'nombre' => 'La Mar', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0506', 'nombre' => 'Lucanas', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0507', 'nombre' => 'Parinacochas', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0508', 'nombre' => 'Páucar del Sara Sara', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0509', 'nombre' => 'Sucre', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0510', 'nombre' => 'Víctor Fajardo', 'departamento_codigo' => '05'],
            ['codigo_ubigeo' => '0511', 'nombre' => 'Vilcas Huamán', 'departamento_codigo' => '05'],

            // Cajamarca (06)
            ['codigo_ubigeo' => '0601', 'nombre' => 'Cajamarca', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0602', 'nombre' => 'Cajabamba', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0603', 'nombre' => 'Celendín', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0604', 'nombre' => 'Chota', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0605', 'nombre' => 'Contumazá', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0606', 'nombre' => 'Cutervo', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0607', 'nombre' => 'Hualgayoc', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0608', 'nombre' => 'Jaén', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0609', 'nombre' => 'San Ignacio', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0610', 'nombre' => 'San Marcos', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0611', 'nombre' => 'San Miguel', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0612', 'nombre' => 'San Pablo', 'departamento_codigo' => '06'],
            ['codigo_ubigeo' => '0613', 'nombre' => 'Santa Cruz', 'departamento_codigo' => '06'],

            // Callao (07)
            ['codigo_ubigeo' => '0701', 'nombre' => 'Callao', 'departamento_codigo' => '07'],

            // Cusco (08)
            ['codigo_ubigeo' => '0801', 'nombre' => 'Cusco', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0802', 'nombre' => 'Acomayo', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0803', 'nombre' => 'Anta', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0804', 'nombre' => 'Calca', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0805', 'nombre' => 'Canas', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0806', 'nombre' => 'Canchis', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0807', 'nombre' => 'Chumbivilcas', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0808', 'nombre' => 'Espinar', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0809', 'nombre' => 'La Convención', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0810', 'nombre' => 'Paruro', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0811', 'nombre' => 'Paucartambo', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0812', 'nombre' => 'Quispicanchi', 'departamento_codigo' => '08'],
            ['codigo_ubigeo' => '0813', 'nombre' => 'Urubamba', 'departamento_codigo' => '08'],

            // Huancavelica (09)
            ['codigo_ubigeo' => '0901', 'nombre' => 'Huancavelica', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0902', 'nombre' => 'Acobamba', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0903', 'nombre' => 'Angaraes', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0904', 'nombre' => 'Castrovirreyna', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0905', 'nombre' => 'Churcampa', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0906', 'nombre' => 'Huaytará', 'departamento_codigo' => '09'],
            ['codigo_ubigeo' => '0907', 'nombre' => 'Tayacaja', 'departamento_codigo' => '09'],

            // Huánuco (10)
            ['codigo_ubigeo' => '1001', 'nombre' => 'Huánuco', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1002', 'nombre' => 'Ambo', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1003', 'nombre' => 'Dos de Mayo', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1004', 'nombre' => 'Huacaybamba', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1005', 'nombre' => 'Huamalíes', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1006', 'nombre' => 'Leoncio Prado', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1007', 'nombre' => 'Marañón', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1008', 'nombre' => 'Pachitea', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1009', 'nombre' => 'Puerto Inca', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1010', 'nombre' => 'Lauricocha', 'departamento_codigo' => '10'],
            ['codigo_ubigeo' => '1011', 'nombre' => 'Yarowilca', 'departamento_codigo' => '10'],

            // Ica (11)
            ['codigo_ubigeo' => '1101', 'nombre' => 'Ica', 'departamento_codigo' => '11'],
            ['codigo_ubigeo' => '1102', 'nombre' => 'Chincha', 'departamento_codigo' => '11'],
            ['codigo_ubigeo' => '1103', 'nombre' => 'Nasca', 'departamento_codigo' => '11'],
            ['codigo_ubigeo' => '1104', 'nombre' => 'Palpa', 'departamento_codigo' => '11'],
            ['codigo_ubigeo' => '1105', 'nombre' => 'Pisco', 'departamento_codigo' => '11'],

            // Junín (12)
            ['codigo_ubigeo' => '1201', 'nombre' => 'Huancayo', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1202', 'nombre' => 'Concepción', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1203', 'nombre' => 'Chanchamayo', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1204', 'nombre' => 'Jauja', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1205', 'nombre' => 'Junín', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1206', 'nombre' => 'Satipo', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1207', 'nombre' => 'Tarma', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1208', 'nombre' => 'Yauli', 'departamento_codigo' => '12'],
            ['codigo_ubigeo' => '1209', 'nombre' => 'Chupaca', 'departamento_codigo' => '12'],

            // La Libertad (13)
            ['codigo_ubigeo' => '1301', 'nombre' => 'Trujillo', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1302', 'nombre' => 'Ascope', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1303', 'nombre' => 'Bolívar', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1304', 'nombre' => 'Chepén', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1305', 'nombre' => 'Julcán', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1306', 'nombre' => 'Otuzco', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1307', 'nombre' => 'Pacasmayo', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1308', 'nombre' => 'Pataz', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1309', 'nombre' => 'Sánchez Carrión', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1310', 'nombre' => 'Santiago de Chuco', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1311', 'nombre' => 'Gran Chimú', 'departamento_codigo' => '13'],
            ['codigo_ubigeo' => '1312', 'nombre' => 'Virú', 'departamento_codigo' => '13'],

            // Lambayeque (14)
            ['codigo_ubigeo' => '1401', 'nombre' => 'Chiclayo', 'departamento_codigo' => '14'],
            ['codigo_ubigeo' => '1402', 'nombre' => 'Ferreñafe', 'departamento_codigo' => '14'],
            ['codigo_ubigeo' => '1403', 'nombre' => 'Lambayeque', 'departamento_codigo' => '14'],

            // Lima (15)
            ['codigo_ubigeo' => '1501', 'nombre' => 'Lima', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1502', 'nombre' => 'Barranca', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1503', 'nombre' => 'Cajatambo', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1504', 'nombre' => 'Canta', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1505', 'nombre' => 'Cañete', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1506', 'nombre' => 'Huaral', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1507', 'nombre' => 'Huarochirí', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1508', 'nombre' => 'Huaura', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1509', 'nombre' => 'Oyón', 'departamento_codigo' => '15'],
            ['codigo_ubigeo' => '1510', 'nombre' => 'Yauyos', 'departamento_codigo' => '15'],

            // Loreto (16)
            ['codigo_ubigeo' => '1601', 'nombre' => 'Maynas', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1602', 'nombre' => 'Alto Amazonas', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1603', 'nombre' => 'Loreto', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1604', 'nombre' => 'Mariscal Ramón Castilla', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1605', 'nombre' => 'Requena', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1606', 'nombre' => 'Ucayali', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1607', 'nombre' => 'Datem del Marañón', 'departamento_codigo' => '16'],
            ['codigo_ubigeo' => '1608', 'nombre' => 'Putumayo', 'departamento_codigo' => '16'],

            // Madre de Dios (17)
            ['codigo_ubigeo' => '1701', 'nombre' => 'Tambopata', 'departamento_codigo' => '17'],
            ['codigo_ubigeo' => '1702', 'nombre' => 'Manu', 'departamento_codigo' => '17'],
            ['codigo_ubigeo' => '1703', 'nombre' => 'Tahuamanu', 'departamento_codigo' => '17'],

            // Moquegua (18)
            ['codigo_ubigeo' => '1801', 'nombre' => 'Mariscal Nieto', 'departamento_codigo' => '18'],
            ['codigo_ubigeo' => '1802', 'nombre' => 'General Sánchez Cerro', 'departamento_codigo' => '18'],
            ['codigo_ubigeo' => '1803', 'nombre' => 'Ilo', 'departamento_codigo' => '18'],

            // Pasco (19)
            ['codigo_ubigeo' => '1901', 'nombre' => 'Pasco', 'departamento_codigo' => '19'],
            ['codigo_ubigeo' => '1902', 'nombre' => 'Daniel Alcides Carrión', 'departamento_codigo' => '19'],
            ['codigo_ubigeo' => '1903', 'nombre' => 'Oxapampa', 'departamento_codigo' => '19'],

            // Piura (20)
            ['codigo_ubigeo' => '2001', 'nombre' => 'Piura', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2002', 'nombre' => 'Ayabaca', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2003', 'nombre' => 'Huancabamba', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2004', 'nombre' => 'Morropón', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2005', 'nombre' => 'Paita', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2006', 'nombre' => 'Sullana', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2007', 'nombre' => 'Talara', 'departamento_codigo' => '20'],
            ['codigo_ubigeo' => '2008', 'nombre' => 'Sechura', 'departamento_codigo' => '20'],

            // Puno (21)
            ['codigo_ubigeo' => '2101', 'nombre' => 'Puno', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2102', 'nombre' => 'Azángaro', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2103', 'nombre' => 'Carabaya', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2104', 'nombre' => 'Chucuito', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2105', 'nombre' => 'El Collao', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2106', 'nombre' => 'Huancané', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2107', 'nombre' => 'Lampa', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2108', 'nombre' => 'Melgar', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2109', 'nombre' => 'Moho', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2110', 'nombre' => 'San Antonio de Putina', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2111', 'nombre' => 'San Román', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2112', 'nombre' => 'Sandia', 'departamento_codigo' => '21'],
            ['codigo_ubigeo' => '2113', 'nombre' => 'Yunguyo', 'departamento_codigo' => '21'],

            // San Martín (22)
            ['codigo_ubigeo' => '2201', 'nombre' => 'Moyobamba', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2202', 'nombre' => 'Bellavista', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2203', 'nombre' => 'El Dorado', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2204', 'nombre' => 'Huallaga', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2205', 'nombre' => 'Lamas', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2206', 'nombre' => 'Mariscal Cáceres', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2207', 'nombre' => 'Picota', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2208', 'nombre' => 'Rioja', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2209', 'nombre' => 'San Martín', 'departamento_codigo' => '22'],
            ['codigo_ubigeo' => '2210', 'nombre' => 'Tocache', 'departamento_codigo' => '22'],

            // Tacna (23)
            ['codigo_ubigeo' => '2301', 'nombre' => 'Tacna', 'departamento_codigo' => '23'],
            ['codigo_ubigeo' => '2302', 'nombre' => 'Candarave', 'departamento_codigo' => '23'],
            ['codigo_ubigeo' => '2303', 'nombre' => 'Jorge Basadre', 'departamento_codigo' => '23'],
            ['codigo_ubigeo' => '2304', 'nombre' => 'Tarata', 'departamento_codigo' => '23'],

            // Tumbes (24)
            ['codigo_ubigeo' => '2401', 'nombre' => 'Tumbes', 'departamento_codigo' => '24'],
            ['codigo_ubigeo' => '2402', 'nombre' => 'Contralmirante Villar', 'departamento_codigo' => '24'],
            ['codigo_ubigeo' => '2403', 'nombre' => 'Zarumilla', 'departamento_codigo' => '24'],

            // Ucayali (25)
            ['codigo_ubigeo' => '2501', 'nombre' => 'Coronel Portillo', 'departamento_codigo' => '25'],
            ['codigo_ubigeo' => '2502', 'nombre' => 'Atalaya', 'departamento_codigo' => '25'],
            ['codigo_ubigeo' => '2503', 'nombre' => 'Padre Abad', 'departamento_codigo' => '25'],
            ['codigo_ubigeo' => '2504', 'nombre' => 'Purús', 'departamento_codigo' => '25'],
        ];


        foreach ($provincias as $provincia) {
            Provincia::create($provincia);
        }
    }
}
