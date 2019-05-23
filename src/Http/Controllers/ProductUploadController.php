<?php
/**
 * Contains the Product Upload controller class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-05-10
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Product\Contracts\Product;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Framework\Contracts\ProductVariant;
use Vanilo\Framework\Models\ProductVariantProxy;
use Vanilo\Framework\Http\Requests\CreateProductVariant;
use Vanilo\Framework\Http\Requests\CreateProduct;
use Vanilo\Framework\Http\Requests\UpdateProductVariant;
use Vanilo\Properties\Models\PropertyValueProxy;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Framework\Http\Requests\UploadProduct;
use Validator;

class ProductUploadController extends BaseController
{
   /**
     * Displays the File Upload Form for New Products/Variants
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('vanilo::product-upload.index');
    }
    /**
     * Creates New Products/Variants by processing uploaded csv
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload(UploadProduct $request)
    {
        $file_path = $request->csv_file->path();
        $line = 0;
        
        try{
            //Validate CSV File
            if (($handle = fopen($file_path, "r")) !== FALSE) {

                $header_row = fgetcsv($handle, 1000, ","); //Skip Header Row
                while (($file_data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $line++;

                    $data = array();
                    list(
                        $data['name'], 
                        $data['price'], 
                        $data['cost'],
                        $data['sku'],
                        $data['stock'],
                        $data['state'],
                    ) = $file_data;
                                
                    $csv_errors = Validator::make(
                        $data, 
                        (new CreateProduct)->rules()
                    )->errors();


                    //If Key Contains "Property" and Value = null/empty return error
                    foreach(array_combine($header_row, $file_data) as $key => $value)
                    {
                        if((strpos(strtolower($key), 'property') !== false) && empty($value))
                        {
                            $csv_errors->add('property', $key . " - Product property cannot be empty, it must contain a value.");
                        }

                        //Property Name in Header Must Contain a Colon for processing
                        if((strpos(strtolower($key), 'property') !== false) && (strpos(strtolower($key), ':') === false))
                        {
                            $csv_errors->add('property', $key . " - Error: Header must be in following format {Property: Property Name}");
                        }
                    }

                    
                    if ($csv_errors->any()) {
                        return redirect()->back()
                        ->withErrors($csv_errors, 'import')
                        ->with('error_line', $line);
                        }
                }
                fclose($handle);
            }

            //Process CSV File
            if (($handle = fopen($file_path, "r")) !== FALSE) {

                $header_row = fgetcsv($handle, 1000, ","); //Skip Header Row
                while (($file_data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $line++;

                    $data = array();
                    list(
                        $data['name'], 
                        $data['price'], 
                        $data['cost'],
                        $data['sku'],
                        $data['stock'],
                        $data['state'],
                    ) = $file_data;

                    //Get List of Product Proerties & Values
                    $properties = [];
                    foreach(array_combine($header_row, $file_data) as $key => $value)
                    {
                        if((strpos(strtolower($key), 'property') !== false))
                        {
                            //Strip Colon, Keep Property Name Only
                            $property_name = trim(substr($key, strpos($key, ':') + 1, strlen($key)));
                            $properties[$property_name] = $value;
                        }
                    }
                    

                    //Find Product By Name
                    $product = ProductProxy::where('name', $data['name'])->first();
                    
                    if(empty($product)){
                        //Create New Product
                        $product = ProductProxy::create($data);
                    }

                    //Create Variant
                    $variant = ProductVariantProxy::create([
                        'sku' => $data['sku'],
                        'price' => $data['price'],
                        'cost' => $data['cost'],
                        'product_id' => $product->id,

                    ]);

                    //Add Properties and PropertyValues
                    foreach($properties as $key => $value)
                    {
                        //Find Property
                        
                        $property_slug = strtolower(str_replace(" ","-", $key));
                        $property = PropertyProxy::where('slug', $property_slug)->first();

                        if(empty($property))
                        {
                            //Create New Property
                            //Need Name, Slug, Type

                            $property = PropertyProxy::create([
                                'name' => $key,
                                'slug' => $property_slug,
                                'type' => 'text'
                            ]);
                        }

                        $property_value = PropertyValueProxy::where(['property_id' => $property->id, 'value' => $value])->first();

                        //Find Priority
                        $priority = 10;
                        $highest_priority_value = PropertyValueProxy::where('property_id',$property->id)->orderBy('priority')->first();

                        if(!empty($highest_priority_value))
                        {
                            $priority += $highest_priority_value->priority;
                        }

                        if(empty($property_value))
                        {
                            //Create New Property Value
                            $property_value = PropertyValueProxy::create([
                                'title' => $value,
                                'value' => $value,
                                'property_id' => $property->id,
                                'priority' => $priority,
                            ]);
                        }

                        $product->addProperty($property);
                        $variant->addPropertyValue($property_value);
                    }
                                
                    
                }
                fclose($handle);
            }


        }catch(\Exception $e){
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }
        



        //Then move file to upload folder
        //$path = $$request->csv_file->store('uploads/product');

        return "All is well";
    }

}