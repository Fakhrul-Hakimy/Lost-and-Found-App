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
    dateFound: '', // Keep this as date format
    fname: '',
    fcontact: ''
  };
  existingItems: any[] = []; // Initialize as empty array
  isItemSelected = false;
  imageUrl: string | null = null;
  imageFile: File | null = null;
  selectedItemId: string | null = null;

  constructor(private http: HttpClient, private navCtrl: NavController) {}

  ngOnInit() {
    this.checkLogin();
    this.fetchExistingItems();
  }

  ionViewWillEnter() {
    this.checkLogin();
    this.fetchExistingItems();
  }

  checkLogin() {
    if (!this.isLoggedIn()) {
      this.navCtrl.navigateRoot('/home');
    }
  }

  isLoggedIn(): boolean {
    const email = localStorage.getItem('email');
    return email !== null;
  }

  onItemNameChange(event: any) {
    const selectedItemId = event.detail.value; // Access the selected value from the event

    // Find the selected item from the existingItems array
    const selectedItem = this.existingItems.find(item => item.id === selectedItemId);

    if (selectedItem) {
      console.log('Selected item ID:', selectedItemId); // Log the ID for debugging
      this.isItemSelected = true;
      this.selectedItemId = selectedItemId; // Set the selected item ID
      this.loadItemDetails(selectedItem); // Load details for the selected item
    } else {
      console.warn('Selected item ID not found in existingItems.');
      this.isItemSelected = false;
      this.resetItemDetails();
    }
  }

  fetchExistingItems() {
    const email = localStorage.getItem('email');
    if (!email) {
      this.showToast('User email not found. Please log in again.');
      return;
    }

    this.http.get<any[]>(`http://192.168.4.169/getItem2.php?email=${email}`).subscribe(
      (data) => {
        console.log('Fetched items:', data); // Log the response data
        this.existingItems = data.map(item => ({
          id: item.id,
          name: item.item_name,
          category: item.category,
          description: item.description,
          date_found: item.date_found, // Ensure date_found is included
          finder_contact: item.finder_contact,
          finder_name: item.finder_name,
          image_path: item.image_path
        }));
        console.log('Processed items:', this.existingItems); // Log the processed items
      },
      (error) => {
        console.error('Error fetching existing items:', error);
        this.showToast('Failed to fetch existing items. Please try again.');
      }
    );
  }

  loadItemDetails(selectedItem: any) {
    this.item.itemName = selectedItem.name || '';
    this.item.category = selectedItem.category || '';
    this.item.description = selectedItem.description || '';
    this.item.dateFound = selectedItem.date_found || ''; // Use date_found
    this.item.fname = selectedItem.finder_name || '';
    this.item.fcontact = selectedItem.finder_contact || '';
    this.imageUrl = selectedItem.image_path ? `http://192.168.4.169:80/${selectedItem.image_path}` : null;
  }

  resetItemDetails() {
    this.item = {
      itemName: '',
      category: '',
      description: '',
      dateFound: '', // Use dateFound
      fname: '',
      fcontact: ''
    };
    this.imageUrl = null;
    this.imageFile = null;
  }

  async captureImage() {
    try {
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
    } catch (error) {
      console.error('Error capturing image:', error);
      this.showToast('An error occurred while capturing the image.');
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
    if (!this.item.itemName || !this.item.category || !this.item.description || !this.item.dateFound || !this.item.fname || !this.item.fcontact) {
      this.showToast('Please fill in all fields.');
      return;
    }

    try {
      this.item.dateFound = new Date(this.item.dateFound).toISOString().split('T')[0]; // Format as YYYY-MM-DD
    } catch (error) {
      this.showToast('Invalid date format. Please provide a valid date.');
      return;
    }

    const email = localStorage.getItem('email');
    if (!email) {
      this.showToast('User email not found. Please log in again.');
      return;
    }

    const formData = new FormData();
    formData.append('itemId', this.selectedItemId!); // Ensure selectedItemId is not null
    formData.append('itemName', this.item.itemName);
    formData.append('category', this.item.category);
    formData.append('description', this.item.description);
    formData.append('dateFound', this.item.dateFound);
    formData.append('fname', this.item.fname);
    formData.append('fcontact', this.item.fcontact);
    formData.append('email', email); // Include user email

    if (this.imageFile) {
      formData.append('image', this.imageFile);
    }

    this.http.post<any>('http://192.168.4.169/processUpdate.php', formData).subscribe(
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
