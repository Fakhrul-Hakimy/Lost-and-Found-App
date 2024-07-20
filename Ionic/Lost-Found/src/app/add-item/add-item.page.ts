import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';
import { Toast } from '@capacitor/toast';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-add-item',
  templateUrl: './add-item.page.html',
  styleUrls: ['./add-item.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
})
export class AddItemPage {
  itemName: string = '';
  category: string = '';
  description: string = '';
  dateFound: string = ''; // Ensure this is initialized properly
  fname: string = '';
  fcontact: string = '';
  imageUrl: string | null = null;
  imageFile: File | null = null;

  constructor(private http: HttpClient,private navCtrl: NavController) {}

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

  addItem() {
    // Validate form inputs
    if (!this.itemName || !this.category || !this.description || !this.dateFound || !this.fname || !this.fcontact || !this.imageUrl) {
      this.showToast('Please fill in all fields and capture an image.');
      return;
    }

    // Convert dateFound to ISO 8601 format
    try {
      this.dateFound = new Date(this.dateFound).toISOString();
    } catch (error) {
      this.showToast('Invalid date format. Please provide a valid date.');
      return;
    }

    const formData = new FormData();
    formData.append('itemName', this.itemName);
    formData.append('category', this.category);
    formData.append('description', this.description);
    formData.append('dateFound', this.dateFound);
    formData.append('fname', this.fname);
    formData.append('fcontact', this.fcontact);
    if (this.imageFile) {
      formData.append('image', this.imageFile);
    }

    this.http.post<any>('http://localhost/Lost-and-Found-App/processItem.php', formData).subscribe(
      (response) => {
        console.log('Item added successfully:', response);
        this.showToast('Item added successfully!');
        // Clear form fields after adding item
        this.itemName = '';
        this.category = '';
        this.description = '';
        this.dateFound = '';
        this.fname = '';
        this.fcontact = '';
        this.imageUrl = null;
        this.imageFile = null;
      },
      (error) => {
        console.error('Error adding item:', error);
        this.showToast('Failed to add item. Please try again.');
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
