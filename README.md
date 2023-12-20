# Telegram Login Widget ile Kullanıcı Kayıt Sistemi

Merhaba, bu proje, Telegram Login Widget kullanarak sıfırdan bir Kullanıcı Kayıt Sistemi oluşturmayı amaçlamaktadır. Bu sistem, PHP ve MySQL'i kullanarak arka planda, HTML ve CSS ile web tabanlı kullanıcı arayüzünde çalışmaktadır.

## Nasıl Kullanılır?

1. **Projeyi İndirme:**
git clone https://github.com/kullaniciadim/telegram-login-system.git


2. **Veritabanı Oluşturma:**
- MySQL veritabanında yeni bir veritabanı oluşturun.
- `config.php` dosyasını düzenleyerek MySQL bağlantı bilgilerinizi güncelleyin.

3. **Telegram Bot Oluşturma:**
- [Telegram BotFather](https://core.telegram.org/bots#botfather) üzerinden yeni bir bot oluşturun.
- `config.php` dosyasındaki `TELEGRAM_BOT_TOKEN` değişkenine botunuzun token'ını ekleyin.

4. **Web Sunucusu Ayarları:**
- Apache veya Nginx gibi bir web sunucusu kullanarak proje dizinini servis edin.

5. **Tarayıcıda Açma:**
- Tarayıcınızda projeyi açın ve kullanıcı kayıt sistemini kullanmaya başlayın!

## Telegram Login Widget Nasıl Çalışır?

- İlk girişte, Telegram widget'ı telefon numaranızı sorar ve yetkilendirmeniz için bir onay mesajı gönderir.
- Bu işlem tamamlandığında, Telegram ile giriş yapmayı destekleyen her web sitesinde iki tıklamayla giriş yapabilirsiniz.
- Giriş yapmak, Telegram adınızı, kullanıcı adınızı ve profil resminizi web sitesi sahibine gönderir.
- Telefon numaranız gizli kalır ve Telegram Login Widget, kullanıcının telefon numarasını size vermez.

Bu sistem aynı zamanda web sitesinin botlarından size mesaj gönderme izni de isteyebilir.

## Katkıda Bulunma

Eğer bu projeye katkıda bulunmak istiyorsanız, lütfen forklayın ve pull request gönderin.

Teşekkürler!
