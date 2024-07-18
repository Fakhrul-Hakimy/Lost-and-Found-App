import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { HttpClientModule } from '@angular/common/http';
import { LostItemService } from '../services/lost-item.service';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';

@Component({
  selector: 'app-add-item',
  templateUrl: './add-item.page.html',
  styleUrls: ['./add-item.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
  providers: [LostItemService]
})
export class AddItemPage {
  itemName: string = '';
  category: string = '';
  description: string = '';
  dateFound: string = '';
  fname: string = '';
  fcontact: string = '';
  image: File | null = null;
  imageUrl: string | null = null;

  constructor(private lostItemService: LostItemService) {}

  async captureImage() {
    const image = await Camera.getPhoto({
      quality: 90,
      allowEditing: false,
      resultType: CameraResultType.DataUrl,
      source: CameraSource.Prompt // Use Prompt to mimic a prompt to select or capture a media
    });
  
    this.imageUrl = image.dataUrl ?? null;
    this.image = this.dataUrlToFile(image.dataUrl!, 'captured-image.jpg');
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
    const formData = new FormData();
    formData.append('itemName', this.itemName);
    formData.append('category', this.category);
    formData.append('description', this.description);
    formData.append('dateFound', this.dateFound);
    formData.append('fname', this.fname);
    formData.append('fcontact', this.fcontact);
    if (this.image) {
      formData.append('image', this.image);
    }

    this.lostItemService.addItem(formData).subscribe(
      (response: any) => {
        console.log('Item added successfully:', response);
      },
      (error: any) => {
        console.error('Error adding item:', error);
      }
    );
  }
}
