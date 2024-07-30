import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, ToastController, LoadingController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-changepass',
  templateUrl: 'changepass.page.html',
  styleUrls: ['changepass.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HttpClientModule,
    RouterModule,
  ],
})
export class ChangepassPage {
  currentpass: string = '';
  newpass: string = '';
  username = localStorage.getItem('username');
  email = localStorage.getItem('email');



  constructor(
    private http: HttpClient, 
    private navCtrl: NavController,
    private toastController: ToastController, // Inject ToastController
    private loadingController: LoadingController // Inject LoadingController
  ) {}

  ngOnInit() {
    this.checkLogin();
  }

  ionViewWillEnter() {
    this.checkLogin();
  }

  checkLogin() {
    if (!this.isLoggedIn()) {
      this.navCtrl.navigateRoot('/home');
    }
  }

  // Method to check if the user is logged in
  isLoggedIn(): boolean {
    const username = localStorage.getItem('username');
    const email = localStorage.getItem('email');
    return username !== null && email !== null;
  }

  async changepass() {
    // Basic validation
    if (!this.currentpass || !this.newpass) {
      this.presentToast('Please enter both new and current password', 'danger');
      return;
    }


    const loginData = {
      currentpass: this.currentpass,
      newpass: this.newpass,
      username:this.username,
      email: this.email
 
    };
    console.log(loginData);

    // Show loading indicator
    const loading = await this.loadingController.create({
      message: 'Processing...',
      duration: 5000
    });
    await loading.present();

    try {
      const response = await this.http.post<any>('http://learning-fish-ideal.ngrok-free.app/processChangepass.php', loginData).toPromise();
      if (response.success) {
        this.presentToast('Change Password successful', 'success');
        this.navCtrl.navigateForward('/home');
      } else {
        this.presentToast(response.message, 'danger');
      }
    } catch (error) {
      console.error('Error:', error);
      this.presentToast('An error occurred', 'danger');
    } finally {
      loading.dismiss(); // Hide loading indicator
    }
  }

  // Method to present a toast message
  async presentToast(message: string, color: string) {
    const toast = await this.toastController.create({
      message: message,
      color: color,
      duration: 2000,
      position: 'top'
    });
    toast.present();
  }
}
