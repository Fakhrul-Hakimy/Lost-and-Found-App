import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { Toast } from '@capacitor/toast';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-update',
  templateUrl: './update.page.html',
  styleUrls: ['./update.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
})
export class UpdatePage implements OnInit {
  item = {
    itemName: '',
    category: '',
    description: '',
    dateFound: '',
    fname: '',
    fcontact: ''
  };
  imageUrl: string | null = null;
  imageFile: File | null = null;

  constructor(private http: HttpClient, private navCtrl: NavController) {}

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

  isLoggedIn(): boolean {
    const username = localStorage.getItem('username');
    const email = localStorage.getItem('email');
    return username !== null && email !== null;
  }

  async captureImage() {
    const image = await Camera.getPhoto({
      quality: 90,
      allowEditing: false,
      resultType: CameraResultType.DataUrl,
      source: CameraSource.Camera,
    });

    if (image.dataUrl) {
      this.imageUrl = image.dataUrl;
      this.imageFile = this.dataUrlToFile(image.dataUrl, 'captured-image.jpg');
    } else {
      this.showToast('Failed to capture image. Please try again.');
    }
  }

  dataUrlToFile(dataUrl: string, fileName: string): File {
    const arr = dataUrl.split(',');
    const mime = arr[0].match(/:(.*?);/)![1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);

    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], fileName, { type: mime });
  }

  updateItem() {
    if (!this.item.itemName || !this.item.category || !this.item.description || !this.item.dateFound || !this.item.fname || !this.item.fcontact || !this.imageUrl) {
      this.showToast('Please fill in all fields and capture an image.');
      return;
    }

    try {
      this.item.dateFound = new Date(this.item.dateFound).toISOString();
    } catch (error) {
      this.showToast('Invalid date format. Please provide a valid date.');
      return;
    }

    const formData = new FormData();
    formData.append('itemId', '1'); // Replace with actual item ID
    formData.append('itemName', this.item.itemName);
    formData.append('category', this.item.category);
    formData.append('description', this.item.description);
    formData.append('dateFound', this.item.dateFound);
    formData.append('fname', this.item.fname);
    formData.append('fcontact', this.item.fcontact);
    if (this.imageFile) {
      formData.append('image', this.imageFile);
    }

    this.http.post<any>('http://localhost/site/processUpdate.php', formData).subscribe(
      (response) => {
        console.log('Item updated successfully:', response);
        this.showToast('Item updated successfully!');
        this.navCtrl.navigateRoot('/main-page');
      },
      (error) => {
        console.error('Error updating item:', error);
        this.showToast('Failed to update item. Please try again.');
      }
    );
  }

  async showToast(message: string) {
    await Toast.show({
      text: message,
      duration: 'short',
    });
  }
}
