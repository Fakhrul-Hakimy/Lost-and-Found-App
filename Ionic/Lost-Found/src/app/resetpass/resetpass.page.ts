import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, ToastController, LoadingController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-resetpass',
  templateUrl: 'resetpass.page.html',
  styleUrls: ['resetpass.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HttpClientModule,
    RouterModule,
  ],
})
export class ResetPage {
  username: string = '';
  email: string = '';

  constructor(
    private http: HttpClient, 
    private navCtrl: NavController,
    private toastController: ToastController, // Inject ToastController
    private loadingController: LoadingController // Inject LoadingController
  ) {}

  async reset() {
    // Basic validation
    if (!this.username || !this.email) {
      this.presentToast('Please enter both username and email', 'danger');
      return;
    }

    const loginData = {
      username: this.username,
      email: this.email
    };

    // Show loading indicator
    const loading = await this.loadingController.create({
      message: 'Processing...',
      duration: 5000
    });
    await loading.present();

    try {
      const response = await this.http.post<any>('http://localhost/site/resetpass.php', loginData).toPromise();
      if (response.success) {
        this.presentToast('Reset successful', 'success');
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
