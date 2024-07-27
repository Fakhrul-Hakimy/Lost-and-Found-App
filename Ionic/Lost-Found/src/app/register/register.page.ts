import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, ToastController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: 'register.page.html',
  styleUrls: ['register.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HttpClientModule,
    RouterModule,
  ],
})
export class RegisterPage {
  email: string = '';
  username: string = '';
  password: string = '';

  constructor(
    private http: HttpClient, 
    private navCtrl: NavController,
    private toastController: ToastController // Inject ToastController
  ) {}

  async register() {
    const loginData = {
      email: this.email,
      username: this.username,
      password: this.password
    };
    
    try {
      const response = await this.http.post<any>('http://localhost/site/processRegister.php', loginData).toPromise();
      if (response.success) {
        this.presentToast('Register successful', 'success');
        this.navCtrl.navigateForward('/home');
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
}
