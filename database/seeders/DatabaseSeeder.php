<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default admin user
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'PAMBUDIANSYAH',
                'email' => 'admin@gundar.ac.id',
                'password' => Hash::make('admin123'),
            ]
        );

        // Seed default categories
        $infoSekolah = Category::firstOrCreate(
            ['name' => 'Info Sekolah'],
            ['description' => 'Pengumuman resmi dan berita seputar kampus Sekolah Tinggi GUNDAR.']
        );

        $seni = Category::firstOrCreate(
            ['name' => 'Seni'],
            ['description' => 'Wadah tulisan kreatif, puisi, cerpen, ilustrasi, dan karya seni mahasiswa.']
        );

        $ilmiah = Category::firstOrCreate(
            ['name' => 'Ilmiah'],
            ['description' => 'Artikel edukatif, karya ilmiah populer, essay, dan tips akademik.']
        );

        // Seed sample articles
        Article::firstOrCreate(
            ['title' => 'Pengumuman Jadwal Ujian Akhir Semester Ganjil 2026/2027'],
            [
                'category_id' => $infoSekolah->id,
                'content' => 'Diberitahukan kepada seluruh mahasiswa Sekolah Tinggi GUNDAR bahwa pelaksanaan Ujian Akhir Semester (UAS) Ganjil akan dimulai pada tanggal 10 Agustus 2026. Harap mempersiapkan KRS dan kartu ujian.',
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&auto=format&fit=crop&q=80',
                'created_by' => $admin->id,
            ]
        );

        Article::firstOrCreate(
            ['title' => 'Antologi Puisi Mahasiswa: Mengukir Senja di Kampus GUNDAR'],
            [
                'category_id' => $seni->id,
                'content' => 'Koleksi puisi hangat dari Komunitas Seni dan Sastra GUNDAR. Menyoroti dinamika kehidupan kampus, persahabatan, dan perjuangan impian di balik koridor ruang kuliah.',
                'image_url' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&auto=format&fit=crop&q=80',
                'created_by' => $admin->id,
            ]
        );

        Article::firstOrCreate(
            ['title' => 'Menciptakan Bayangan Palsu Yang Indah'],
            [
                'category_id' => $seni->id,
                'content' => "Harapan muncul dari terlihatnya bayangan keindahan yang belum pasti. Rasa arogan yang muncul karena rasa abai terhadap kenyataan, menganggap perbedaan adalah suatu kekuatan padahal tak ada takaran yang pasti bahwa itu akan menjadi kekuatan. Penolakan kenyataan ini seharusnya tidak boleh ada, karena bukan kenyataan yang kita tolak, tapi cara pandang kita yang berubah membuat kenyataan menjadi menyakitkan. Kita terlalu hidup dalam bayangan keindahan. Harusnya manusia hidup dengan melihat rasa sakit atas kenyataan yang diberikan adalah bentuk kenikmatan atas eksistensinya.\n\n# Membakar Cermin di Tengah Hutan\n\nCara pandang manusia dalam melihat dunia selalu berbeda-beda, ada yang melihatnya sebagai sesuatu bentuk kegagalan eksistensi, menafsirkan bahwa adanya manusia itu tak memiliki arti yang minim; dan cenderung dilebih-lebihkan, atau bahkan bisa saja dilihat sebagai tanpa arti. Ada pula yang melihat dunia seperti orang religius yang selalu melihat semuanya secara terarah karena percaya pada sebuah virtue. Sama halnya dengan orang-orang yang terus memuja kehidupan mereka; orang-orang yang terus mengikuti kenikmatan dunia hingga mereka tenggelam di dalamnya. Ada juga yang melihat dunia secara abstrak, mereka yang melihat bahwa mereka mempunyai pilihan di hidupnya, orang-orang ini kadang justru lebih memilih untuk tidak terlalu memedulikan esensi di balik eksistensi, dan hanya fokus pada tujuan-tujuan dasar; seperti bekerja untuk hidup atau untuk membeli sesuatu.\n\nBerbagai cara pandang ini menciptakan banyaknya kemungkinan kebenaran dari hidup itu sendiri. Atau bisa saja esensi dari hidup itu lebih kompleks, seperti yang sering di lantangkan oleh filsuf-filsuf yang sering orang bilang gila itu, bisa saja dari mereka ada kebenaran. Atau justru dengan banyaknya pikiran-pikiran yang muncul di kepala para pemikir ini justru bahkan eksistensi dari idenya memiliki kemungkinan . Membuat semuanya tetap terasa kabur. Dalam kondisi seperti ini hal tak terduga bisa terjadi, seperti dari banyak orang yang berpikir bahwa kebenaran sejati itu ada, bisa saja salah satunya itu bukan muncul dari salah seorang tokoh pemikir, tapi dari sepotong celetukan pikiran kita pada malam hari, tapi tetap saja tak ada yang tahu soal itu.\n\nTapi bagaimana jika semua pandangan itu hanyalah sebuah bentuk ilusi yang kita ciptakan sendiri? Bagaimana jika argumen tersebut itu muncul karena kita secara tidak sadar mengharapkan kebenaran di hidup yang terlalu rumit ini? Bagaimana jika kebenaran itu hanya ada karena kita, manusia mencoba memikirkannya? Segala pemikiran yang muncul di manusia justru adalah fondasi kebenaran itu sendiri, sebuah objektivitas itu ada karena banyak orang melihatnya sebagai suatu hal yang benar secara bersamaan. Manusia dari berbagai zaman mensimulasikan kehidupan yang menurutnya benar secara personal, dan selalu ada hal yang mendasari pemikiran itu muncul, entah dari sebuah ajaran atau dari sebuah kebiasaan. Maka dari itu sepertinya untuk mengatakan bahwa kita atau mereka itu salah atau benar itu tidak bisa sepenuhnya akurat. Semua hal yang diputuskan itu secara tidak langsung adalah sebuah keputusan bersama. Sebuah keputusan yang tercipta karena adanya pandangan yang tercipta.\n\n# **Melihat Asap dengan Rasa Perih**\n\nDalam begitu banyaknya cara pandang ini manusia pun di tempatkan pada posisi yang sulit, cukup sulit untuk dikatakan. Mereka merasakan adanya semacam sebuah ketakutan yang terus mengikuti, ketakutan akan sebuah kondisi di mana mereka kehilangan makna. Tapi paksaan untuk mendapatkan makna mereka pun tak bisa mereka tangani, karena rasa takut untuk melangkah. Alasan ini mungkin sedikit terlalu filosofis tapi rasa-rasanya itu adalah dasarnya. Mereka takut untuk melangkah karena banyak hal. Mereka bisa saja takut karena melihat adanya semacam omong kosong untuk melangkah, mereka tidak punya cukup alasan untuk benar-benar memijakkan kaki untuk memulai, tapi mereka juga terus ditekan oleh rasa tidak nyaman yang menggerogoti batin mereka. Atau bisa saja mereka takut melangkah karena adanya latar belakang yang menahan langkah mereka, entah itu seperti pemikiran yang sudah tertanam yang menolak untuk dibuang, atau kondisi eksternal yang tidak pernah memberikan cukup ruang.\n\nKondisi seperti pengikatan seperti ini yang membuat manusia terus diberikan bayang-bayang yang kabur akan tujuan. Mereka seperti berharap atau bahkan melihat secara sekilas adanya tujuan yang murni, tapi adanya kabut yang terus menyelimuti membuat keraguan terus bertahan, bahkan mengkis harapan. Manusia itu hidup dengan persepsi, mereka akan melihat, menganalisa, dan menyimpulkan. Jika melihat posisi ini manusia terjebak pada bagian menganalisa, atau banyak dari mereka yang tertahan di melihat. Kehilangan bagian dari apa yang menciptakan mereka ini yang membuat manusia tersiksa, manusia secara naluriah terus menciptakan makna, alasannya pun tidak bisa dijelaskan karena memang ada secara abstrak, dan ketika mereka tidak mendapatkannya manusia akan merasakan perasaan yang tidak nyaman itu.\n\nManusia berada di posisi di mana mereka terus merasakan paksaan, manusia akan merasakan angst; sebuah perasaan di mana kebebasan yang di terima manusia untuk memilih terasa menjadi beban. Mereka seperti tidak punya ruang untuk berpikir, dan di wajibkan untuk tergesa-gesa, mereka benar-benar merasakan posisi yang tidak nyaman, seperti ketika seorang terimpit oleh dinding yang terus bergerak menelannya.\n\n# Munculnya Penglihatan (Sepertinya)\n\nPerasaan memaksa yang ada terus menekan manusia. Manusia dipaksa untuk terus berada dalam tekanan, mereka seakan-akan di wajibkan untuk hidup dalam penderitaan. Perasaan ini terus berlangsung, tak pernah berhenti, bahkan ketika manusia sudah mencoba menerimanya hingga muntah karenanya. Manusia yang memang pada dasarnya tidak bisa terus di tekan, terus melakukan perlawanan, namun pada akhirnya tetap saja perlawanan batin yang mereka hadirkan tidak akan pernah cukup untuk melawan paksaan tersebut.\n\nSetelah terus menerus mencoba, dari dalam diri manusia akan muncul sebuah kesadaran; kesadaran yang negatif. Mereka akan menjadi pasif, bukan karena pesimis, tapi karena mereka melihat adanya hal lain. Mereka melihat bahwa perlawanan yang mereka berikan itu sebenarnya hanyalah sebuah hal yang tidak penting; dalam benak mereka. Mereka berpikir bahwa alasan untuk melawan itu hanya sekedar pemikiran yang muncul dari reflek, bukannya dari berpikir.\n\nTertutupnya kemungkinan untuk berpikir ini membuat manusia bukannya lagi mencari cahaya untuk di lihat, tapi hanya berfokus untuk melihat saja. Manusia pada akhirnya akan menciptakan sebuah penglihatan baru, penglihatan di mana mereka merasa cocok di dalamnya. Sebuah penglihatan yang mungkin mereka jadikan pelarian, tapi mungkin pelarian ini lebih baik daripada harus menderita secara batin; karena hidupnya terus berjalan secara tidak nyaman.\n\nBayangan pun tercipta, bukan karena mereka ingin membayangkan sebuah keindahan, tapi karena mereka ditekan oleh sebuah keadaan, sebuah keadaan yang muncul dari perasaan batin yang tersiksa. Membuat bayangan yang baru (mungkin palsu atau mungkin saja tidak) ini terasa begitu masuk akal untuk menjadi alasan mereka hidup.\n\nManusia pun hidup dalam kondisi yang bahkan mereka tidak pernah minta itu untuk ada, bayangan muncul dari rasa tersiksa. Perasaan pencarian kenyamanan itu ada; dengan bayaran ditinggalkannya sebuah keaslian, demi rasa nyaman (yang palsu).",
                'image_url' => '/images/bayangan-palsu.png',
                'created_by' => $admin->id,
            ]
        );

        Article::firstOrCreate(
            ['title' => 'Pemanfaatan Artificial Intelligence dalam Riset Akademik Modern'],
            [
                'category_id' => $ilmiah->id,
                'content' => 'Perkembangan AI generatif dan kecerdasan buatan membuka peluang besar sekaligus tantangan etis dalam penulisan jurnal ilmiah. Artikel ini mengulas strategi pemanfaatan AI yang bertanggung jawab.',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80',
                'created_by' => $admin->id,
            ]
        );
    }
}
