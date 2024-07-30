import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, ToastController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HttpClientModule,
    RouterModule,
  ],
})
export class HomePage {
  username: string = '';
  password: string = '';

  constructor(
    private http: HttpClient, 
    private navCtrl: NavController,
    private toastController: ToastController // Inject ToastController
  ) {}

  async login() {
    const loginData = {
      username: this.username,
      password: this.password
    };

    try {
      const response = await this.http.post<any>('http://learning-fish-ideal.ngrok-free.app/processLogin.php', loginData).toPromise();
      if (response.success) {
        // Save username and email in localStorage
        localStorage.setItem('username', response.data.username);
        localStorage.setItem('email', response.data.email);

        this.presentToast('Login successful', 'success');
        this.navCtrl.navigateForward('/main-page');
      } else {
        this.presentToast(response.message, 'danger');
      }
    } catch (error) {
      console.error('Error:', error);
      this.presentToast('An error occurred', 'danger');
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

  // Method to check if the user is logged in
  isLoggedIn(): boolean {
    const username = localStorage.getItem('username');
    const email = localStorage.getItem('email');
    return username !== null && email !== null;
  }

  // Method to logout
  logout() {
    localStorage.removeItem('username');
    localStorage.removeItem('email');
    this.navCtrl.navigateRoot('/login');
  }
}
